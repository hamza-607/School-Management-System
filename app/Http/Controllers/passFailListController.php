<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Section;
use App\Models\SectionSubjectTeacher;
use App\Models\Student;
use App\Models\StudentEnrollment;
use Auth;
use Illuminate\Http\Request;

class passFailListController extends Controller
{
    public function index()
    {
        $academicYears = AcademicYear::with('sections')->orderByDesc('name')->get();

        return view('pass-fail-lists.index', [
            'academicYears' => $academicYears,
        ]);
    }

    public function create($sectionID, Request $request)
    {
        // dd($sectionID);

        $section = Section::with('grade')->findOrFail($sectionID);
        $year = $section->academic_year;

        // dd($year);
        $dataSemesters = $year->semesters()->get(['id', 'name']);
        // dd($dataSemesters);

        $studentIDs = $section->student_enrollments->pluck('student_id');
        $query = Student::query()->whereIn('id', $studentIDs)->where('is_active', 1);

        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $dataStudents = $query->paginate($request->per_page ?? 10)->withQueryString();

        $students = [];
        foreach ($dataStudents as $index => $student) {
            $studentEnrollment = $student->student_enrollments()->where('section_id', $sectionID)->first();

            $students[$index]['id'] = $student->id;
            $students[$index]['name'] = $student->name;
            $students[$index]['result'] = $studentEnrollment ? $studentEnrollment->result : null;
            $marks = [];
            $yearMark = 0;
            $failes = [];
            $penalties = [];

            //العلامات
            foreach ($dataSemesters as $semester) {
                // dd($semester);
                $dataMarks = $student
                    ->student_scores()
                    ->join('score_components', 'student_scores.score_component_id', '=', 'score_components.id')
                    ->where('semester_id', $semester->id)
                    ->get(['student_scores.score', 'score_components.max_score']);

                $totalScore = $dataMarks->sum('score');
                $totalMaxScore = $dataMarks->sum('max_score');
                // $marks[] = $totalMaxScore > 0 ? $totalScore . '(' . . '%)' . '/' . $totalMaxScore . '(' . '100%' . ')' : 0;
                $marks[] = [
                    'score' => $totalScore,
                    'max' => $totalMaxScore,
                    'percent' => $totalMaxScore > 0 ? number_format(((100 * $totalScore) / $totalMaxScore), 3) : 0,
                ];
                $yearMark += $totalMaxScore > 0 ? (100 * $totalScore) / $totalMaxScore : 0;

                // dd($totalScore);
            }
            // dd($yearMark/count($marks));
            $students[$index]['marks'] = $marks;
            $students[$index]['yearMark'] = number_format($yearMark / count($marks), 3) . '%';

            //تفاصيل المواد الراسبة
            $scores = $student->student_scores()
                ->join('score_components', 'student_scores.score_component_id', '=', 'score_components.id')
                ->join('finished_sessions', 'score_components.finished_session_id', '=', 'finished_sessions.id')
                ->whereIn('semester_id', $dataSemesters->pluck('id')->toArray())
                ->get([
                    'subject_id',
                    'score',
                    'max_score'
                ]);

            // dd($scores, $dataSemesters->pluck('id')->toArray());

            $subjects = $subjects = SectionSubjectTeacher::where('section_id', $sectionID)
                ->with('subject:id,name')
                ->get()
                ->pluck('subject')
                ->unique('id')
                ->toArray();

            // dd($subjects);
            foreach ($subjects as $subject) {
                // dd($scores,$subjectID,$scores->where('subject_id', 3));
                $subjectScores = $scores->where('subject_id', $subject['id']); // $subject->id
                $totalMaxScore = $subjectScores->sum('max_score');
                $totalScore = $subjectScores->sum('score');
                // dd($totalMaxScore/2, $totalScore);
                if ($totalScore < ($totalMaxScore / 2)) {
                    $failes[] = [
                        'name' => $subject['name'],
                        // 'mark' => $totalMaxScore > 0 ? $totalScore . '(' . number_format(((100 * $totalScore) / $totalMaxScore), 3) . '%)' . '/' . $totalMaxScore . '(' . '100%' . ')' : 0
                        'mark' => [
                            'score' => $totalScore,
                            'max' => $totalMaxScore,
                            'percent' => $totalMaxScore > 0 ? number_format(((100 * $totalScore) / $totalMaxScore), 3) : 0,
                        ]
                    ];
                }
            }
            $students[$index]['failes'] = $failes;

            //العقوبات
            // dd($student->penalties);
            foreach ($student->penalties as $penaltie) {
                $penalties[] = [
                    'type' => $penaltie->penalty_type,
                    'date' => $penaltie->created_at->format('Y-m-d'),
                    'status' => $penaltie->status,
                ];
            }

            $students[$index]['penalties'] = $penalties;
        }

        $year = $section->academic_year;

        return view('pass-fail-lists.create', [
            'year' => $year,
            'section' => $section,
            'semesters' => $dataSemesters,
            'students' => $students
        ]);
    }

    public function store(Request $request, $sectionID)
    {
        // dd($request->all());

        try {
            $validated = $request->validate([
                'students' => 'required|array',
                'students.*.student_id' => 'required|exists:students,id',
                'students.*.result' => 'required|in:passed,failed',
            ]);

            if ($validated['students']) {
                foreach ($validated['students'] as $student) {
                    $studentEnrollment = StudentEnrollment::where('section_id', $sectionID)
                        ->where('student_id', $student['student_id'])
                        ->first();

                    $studentEnrollment->update([
                        "result" => $student['result'],
                        "decided_by" => Auth::user()->staff->id,
                        "decided_at" => now(),
                    ]);

                    $sectionID = $request->route('sectionID');

                    $thisYear = Section::findOrFail($sectionID)->academic_year;

                    $nextYear = AcademicYear::where('start_date', '>', $thisYear->start_date)
                        ->orderBy('start_date', 'asc')->first();
                    // dd($nextYear);

                    StudentEnrollment::firstOrCreate([
                        'student_id' => $student['student_id'],
                        'academic_year_id' => $nextYear ? $nextYear->id : null,
                    ], [
                        'student_id' => $student['student_id'],
                        'academic_year_id' => $nextYear ? $nextYear->id : null,
                        'section_id' => null,
                        'grade_id' => $studentEnrollment->result === 'passed' ? $studentEnrollment->grade->next_grade_id : $studentEnrollment->grade_id,
                        'result' => null,
                        'decided_by' => null,
                        'decided_at' => null,
                        'updated_by' => null,
                    ]);
                }
            }

            return redirect()->route('passFailLists.index')->with('success', ['تم حفظ النتائج بنجاح.', 'تم تسجيل الطلاب في السنة الدراسية التالية بنجاح.'])->with('alert', ['يجب أن يتم تسجيل الطلاب في الشعبة المناسبة من خلال قسم الطلاب', 'يجب أن يتم تفعيل السنة الدراسية "' . $nextYear ? $nextYear->name : 'غير متوفرة' . '" من خلال قسم السنوات الدراسية بعد الانتهاء من وضع النتائج لجميع الشعب']);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء إضافة صف: ' . $e->getMessage());
        }
    }
}
