<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScoreRequest;
use App\Models\AcademicYear;
use App\Models\ScoreComponent;
use App\Models\SectionSubjectTeacher;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentScore;
use App\Models\Subject;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($studentID, Request $request)
    {
        $theAcademicYear_id = AcademicYear::where('is_current', 1)
            ->first()->id;
        $semesters = Semester::where('academic_year_id', $theAcademicYear_id)
            ->get(['id', 'name'])
            ->toArray();

        // dd($semesters);

        $theStudent = Student::findOrFail($studentID);
        $subjectIDs = $theStudent->section->section_subject_teachers->unique('subject_id')
            ->pluck('subject_id')
            ->toArray();
        $subjects = Subject::whereIn('id', $subjectIDs)
            ->get(['id', 'name'])
            ->toArray();

        // dd($subjects);
        // dd($studentID);
        $scoresAsModes = ScoreComponent::join('student_scores', 'student_scores.score_component_id', '=', 'score_components.id')
            ->join('semesters', 'semesters.id', '=', 'score_components.semester_id')
            ->join('finished_sessions', 'finished_sessions.id', '=', 'score_components.finished_session_id')
            ->where('academic_year_id', $theAcademicYear_id)
            ->where('student_id', $studentID)
            ->get([
                'score_components.name as scoreName',
                'score_components.type as scoreType',
                'student_scores.score as scoreValue',
                'student_scores.id as scoreID',
                'score_components.max_score as scoreMaxValue',
                'finished_sessions.subject_id as subjectID',
                'score_components.semester_id'
            ]);
        // dd($scoresAsModes);

        $scoresAsArray = [];

        foreach ($subjects as $subject) {
            // dd($subject);
            $semestersScores = [];

            foreach ($semesters as $index => $semester) {

                $subjectSemesterScores = $scoresAsModes->where('subjectID', $subject['id'])
                    ->where('semester_id', $semester['id']);

                $final = $subjectSemesterScores->where('scoreType', 'final_exam')
                    ->first();
                // dd($final);

                $quizzes = $subjectSemesterScores->where('scoreType', 'quiz');
                // dd($quizzes);

                $semestersScores[$index + 1] = [
                    'id'      => $semester['id'],
                    'final'   => $final ? $final->toArray() : null,
                    'quizzes' => $quizzes ? $quizzes->toArray() : null,
                ];
            }
            // dd($semestersScores);

            $subject['semesters'] = $semestersScores;
            $scoresAsArray[] = $subject;
        }
        // dd($scoresAsArray);
        // dd($theStudent, $semesters, $scoresAsArray);
        return view('students.scores.index', [
            'theStudent' => $theStudent,
            'semesters' => $semesters,
            'scoresAsArray' => $scoresAsArray,
            'updateScores' => $request->updateScores ?? false,
        ]);
    }

    public function storeQuiz(Request $request, $sectionID, $sectionSubjectTeacherID)
    {
        // dd($request->all(), $sectionID, $sectionSubjectTeacherID);

        try {
            $session = SectionSubjectTeacher::findOrFail($sectionSubjectTeacherID);

            // ScoreComponent::create([
            //     'finished_session_id' => null,
            //     'semester_id',
            //     'type',
            //     'max_score',
            //     'created_at',
            //     'updated_at'
            // ]);

            $session->update([
                'type' => 'quiz',
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ اثناء اضافة المذاكرة' . $e->getMessage());
        }
    }



    public function updateScores(ScoreRequest $request, $studentID)
    {
        // dd($request->all());

        //   "scores" => array:14 [▼
        //     0 => array:1 [▼
        //       "name" => "الأحياء"
        //     ]
        //     1 => array:2 [▼
        //       "name" => "الرياضيات"
        //       "semesters" => array:2 [▼
        //         1 => array:2 [▼
        //           "quizzes" => array:1 [▼
        //             2 => "80.00"
        //           ]
        //           "final" => "20.00"
        //         ]
        //         2 => array:1 [▼
        //           "quizzes" => array:1 [▼
        //             0 => "20.00"
        //           ]
        //         ]
        //       ]
        //     ]
        //     2 => array:1 [▼
        //       "name" => "فيزياء"
        //     ]
        //     3 => array:1 [▼
        //       "name" => "كيمياء"
        //     ]
        //     10 => array:1 [▼
        //       "name" => "علوم عامة(فيزياء-كيمياء-علوم)"
        //     ]


        try {
            // dd($scores);

            $validated = $request->validated();

            // dd($validated);

            if ($validated['scores']) {
                foreach ($validated['scores'] as $subIndex => $oneSubject) {
                    // dd($oneSubject);
                    if ($oneSubject['semesters']) {
                        foreach ($oneSubject['semesters'] as $semIndex => $oneSemester) {
                            // dd($oneSemester);
                            $isFinalEmpty = $oneSemester['final'] ?? null;
                            if ($isFinalEmpty) {
                                // dd($isFinalEmpty, $oneSemester);
                                $oldFinal = StudentScore::findOrFail($oneSemester['final']['id']);
                                // dd($oldFinal);
                                $oldFinal->update([
                                    'score' => $oneSemester['final']['value'],
                                ]);
                            }

                            $isQuizzesEmpty = $oneSemester['quizzes'] ?? null;
                            if ($isQuizzesEmpty) {
                                // dd($isQuizzesEmpty, $oneSemester);
                                foreach ($oneSemester['quizzes'] as $quizIndex => $oneQuiz) {
                                    $oldQuiz = StudentScore::findOrFail($oneQuiz['id']);
                                    $oldQuiz->update([
                                        'score' => $oneQuiz['value'],
                                    ]);
                                }
                            }
                        }
                    }
                }
            }

            return back()->with('success', 'تم تعديل العلامات بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ اثناء تعديل العلامات' . $e->getMessage());
        }
    }
}
