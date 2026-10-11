<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\ScoreComponent;
use App\Models\Section;
use App\Models\SectionSubjectTeacher;
use App\Models\Semester;
use App\Models\StudentScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class SectionScoresController extends Controller
{
    public function index()
    {
        // dd();
        $sectionIDs = SectionSubjectTeacher::where('teacher_id', Auth::user()->id)->pluck('section_id')->toArray();
        $academicYearID = session('newCurrentYearID') ?? AcademicYear::where('is_current', true)->first()->id;

        $sections = Section::where('academic_year_id', $academicYearID)->whereIn('id', $sectionIDs)
            ->with(['grade', 'student_enrollments'])
            ->get();

        return view('grades_sections.sections.addScores.index', [
            'sections' => $sections,
        ]);
    }

    public function create($sectionID)
    {
        // dd($sectionID);
        $section = Section::findOrFail($sectionID);
        $semesterID = session('newCurrentSemesterID');
        if ($semesterID) {
            $semester = Semester::findOrFail($semesterID);
        } else {
            $semester = Semester::where('is_current', 1)->first();
        }
        // dd(Auth::user()->id, $sectionID);
        $scoreComponents = $semester->score_components()->whereHas('finished_session', function ($query) use ($sectionID) {
            $query->where('teacher_id', Auth::user()->id)->where('section_id', $sectionID);
        })->get();
        // dd($scoreComponents);

        $student_enrollments = $section->student_enrollments()->with('student')->get();

        return view('grades_sections.sections.addScores.create', [
            'section' => $section,
            'student_enrollments' => $student_enrollments,
            'scoreComponents' => $scoreComponents,
            'semester' => $semester,
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        // dd($request->has('students.scores'));
        try {

            //      0 => array:2 [▼
            //   "student" => "7"
            //   "scores" => array:4 [▼
            //     18 => "20"
            //     19 => "7.96"
            //     20 => "10"
            //     21 => "90"
            //   ]
            // ]
            $validated = $request->validate([
                'students' => 'required|array',
                'students.*.student' => 'required|exists:students,id',
                'students.*.scores' => 'required|array',
                'students.*.scores.*' => 'required|numeric|min:0',
            ]);

            // dd($validated);
            if ($request->has('students')) {
                foreach ($validated['students'] as $studentIndex => $oneStudent) {
                    // dd($student['scores']);
                    if ($oneStudent['scores'] !== null) {
                        foreach ($oneStudent['scores'] as $scoreID => $score) {
                            // dd($scoreID,$score);
                            $maxScore = ScoreComponent::findOrFail($scoreID)->max_score;
                            // dd($maxScore);
                            if ($score > $maxScore) {
                                return back()->withInput()->with('error', 'لقد تم تجاوز الحد المسموح به للعلامة المحددة من قبل المدرس ,والتي هي' . $maxScore);
                            }
                            StudentScore::create([
                                'student_id' => $oneStudent['student'],
                                'score_component_id' => $scoreID,
                                'score' => $score,
                                'notes' => null,
                            ]);
                        }

                    }
                }
            }

            return redirect()->route('sectionScores.index')->with('success', 'تم إضافة علامات الطلاب بنجاح');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'حدث خطأ أثناء إضافة العلامات: ' . $e->getMessage());
        }
    }
}
