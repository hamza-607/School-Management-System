<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;

class AttendanceRecordController extends Controller
{
    public function studentIndex(Request $request, $studentID)
    {
        $academicYearID = session('newCurrentYearID') ?? AcademicYear::where('is_current', true)->first()->id;

        $yearName = AcademicYear::findOrFail($academicYearID)->name;
        $student = Student::findOrFail($studentID);
        $section = $student->student_enrollments()->where('academic_year_id', $academicYearID)->first()->section;

        $subjects = [];
        if ($section) {
            $subjectIDs = $section->finished_sessions
                ->unique('subject_id')
                ->pluck('subject_id')
                ->toArray();
            $subjects = Subject::whereIn('id', $subjectIDs)->get();
        }

        $query = Attendance::where('student_id', $studentID)
            ->whereHas('section', function ($q) use ($academicYearID) {
                $q->where('academic_year_id', $academicYearID);
            })->with([
                    'finishedSession',
                    'finishedSession.appointment',
                    'finishedSession.subject',
                    'finishedSession.staff',
                ]);

        if ($request->has('subject_id')) {
            $query->whereHas('finishedSession', function ($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('month')) {
            $query->whereHas('finishedSession', function ($q) use ($request) {
                $q->whereMonth('created_at', $request->month);
            });
        }


        $attendances = $query->get();

        $countOfPresent = $attendances->where('status', 'present')->count();
        $countOfExcused = $attendances->where('status', 'excused')->count();
        $countOfAbsent = $attendances->where('status', 'absent')->count();
        $countOfAttendances = $countOfPresent + $countOfExcused + $countOfAbsent;
        $rateOfAttendances = $countOfAttendances > 0 ? (($countOfPresent + $countOfExcused) / $countOfAttendances) * 100 : 0;

        return view('students.attendance_records.attendance_record', [
            'yearName' => $yearName,
            'theStudent' => $student,
            'subjects' => $subjects,
            'attendances' => $attendances,
            'countOfPresent' => $countOfPresent,
            'countOfExcused' => $countOfExcused,
            'countOfAbsent' => $countOfAbsent,
            'countOfAttendances' => $countOfAttendances,
            'rateOfAttendances' => $rateOfAttendances,
        ]);
    }

    public function sectionIndex(Request $request, $sectionID)
    {
        // dd($request->all());

        $section = Section::with('student_enrollments.student.student_attendances')->findOrFail($sectionID);

        $query = $section->finished_sessions()->with([
            'appointment',
            'subject',
            'staff',
            'attendances',
        ])->orderBy('created_at', 'asc');

        $subjectIDs = $section->finished_sessions
            ->unique('subject_id')
            ->pluck('subject_id')
            ->toArray();
        $subjects = Subject::whereIn('id', $subjectIDs)->get();

        $presentAttendance = Attendance::where('section_id', $sectionID)
            ->where('status', 'present');

        if ($request->has('subject_id')) {
            $query->where('subject_id', $request->subject_id);
            $presentAttendance->whereHas('finishedSession', function ($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }

        if ($request->has('month')) {
            $query->whereMonth('created_at', $request->month);
            $presentAttendance->whereHas('finishedSession', function ($q) use ($request) {
                $q->whereMonth('created_at', $request->month);
            });
        }

        $finished_sessions = $query->get();

        $presentAttendanceCount = (float) $presentAttendance->count();

        $finishedSessionsCount = (float) $finished_sessions->count();
        $studentCount = (float) $section->student_enrollments()
            ->count();

        $tot = $finishedSessionsCount * $studentCount;
        $avgAttendance = $tot > 0 ? ($presentAttendanceCount / $tot) * 100 : 0;

        return view('grades_sections.sections.attendance_records.attendance_record', [
            'theSection' => $section,
            'finished_sessions' => $finished_sessions,
            'subjects' => $subjects,
            'avgAttendance' => $avgAttendance,
        ]);
    }

    public function pdfAttendanceStudentPage(Request $request, $studentID)
    {
        $academicYearID = session('newCurrentYearID') ?? AcademicYear::where('is_current', true)->first()->id;
        $filters = [];

        $year = AcademicYear::findOrFail($academicYearID);
        $student = Student::findOrFail($studentID);
        $section = $student->student_enrollments()->where('academic_year_id', $academicYearID)->first()->section;

        $query = Attendance::where('student_id', $studentID)
            ->whereHas('section', function ($q) use ($academicYearID) {
                $q->where('academic_year_id', $academicYearID);
            })->with([
                    'finishedSession',
                    'finishedSession.appointment',
                    'finishedSession.subject',
                    'finishedSession.staff',
                ]);

        if ($request->has('subject_id')) {
            $filters[] = ['label' => 'المادة', 'value' => Subject::find($request->subject_id)->name];
            $query->whereHas('finishedSession', function ($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }

        if ($request->has('status')) {
            $filters[] = ['label' => 'الحالة', 'value' => $request->status === 'present' ? 'حاضر' : ($request->status === 'excused' ? 'عذر' : 'غائب')];
            $query->where('status', $request->status);
        }

        if ($request->has('month')) {
            $filters[] = ['label' => 'الشهر', 'value' => $request->month];
            $query->whereHas('finishedSession', function ($q) use ($request) {
                $q->whereMonth('created_at', $request->month);
            });
        }

        $daysMap = [
            'sunday' => 'الأحد',
            'monday' => 'الاثنين',
            'tuesday' => 'الثلاثاء',
            'wednesday' => 'الأربعاء',
            'thursday' => 'الخميس',
            'friday' => 'الجمعة',
            'saturday' => 'السبت'
        ];

        $attendances = $query->get();

        $attendancesAsArray = $attendances->map(function ($attendances) use ($daysMap) {
            $finishedSession = $attendances->finishedSession;
            $appointment = $finishedSession->appointment;
            return [
                'التاريخ' => $finishedSession->created_at->format('Y-m-d'),
                'اليوم' => $daysMap[$appointment->day],
                'المادة' => $finishedSession->subject->name,
                'المعلم' => $finishedSession->staff->name,
                'الحالة' => $attendances->status === 'present' ? 'حاضر' : ($attendances->status === 'excused' ? 'عذر' : 'غائب'),
            ];
        })
            ->toArray();

        $countOfPresent = $attendances->where('status', 'present')->count();
        $countOfExcused = $attendances->where('status', 'excused')->count();
        $countOfAbsent = $attendances->where('status', 'absent')->count();
        $countOfAttendances = $countOfPresent + $countOfExcused + $countOfAbsent;
        $rateOfAttendances = $countOfAttendances > 0 ? (($countOfPresent + $countOfExcused) / $countOfAttendances) * 100 : 0;

        $moreInfo = [
            ['label' => 'عدد حصص الحضور', 'value' => $countOfPresent],
            ['label' => 'عدد حصص العذر', 'value' => $countOfExcused],
            ['label' => 'عدد حصص الغياب', 'value' => $countOfAbsent],
            ['label' => 'نسبة الحضور', 'value' => number_format($rateOfAttendances, 2) . '%'],
        ];

        return view('students.PDF.attendance_record', [
            'moreInfo' => $moreInfo,
            'filters' => $filters,
            'year' => $year,
            'theStudent' => $student,
            'attendancesAsArray' => $attendancesAsArray,
            'countOfAttendances' => $countOfAttendances,
        ]);
    }

    public function pdfAttendanceSectionPage(Request $request, $sectionID)
    {
        $section = Section::with('student_enrollments.student.student_attendances')->findOrFail($sectionID);
        $filters = [];
        $studentEnrollments = $section->student_enrollments;

        $query = $section->finished_sessions()->with([
            'subject',
            'staff',
            'attendances',
        ])->orderBy('created_at', 'asc');

        $presentAttendance = Attendance::where('section_id', $sectionID)
            ->where('status', 'present');

        if ($request->has('subject_id')) {
            $filters[] = ['label' => 'المادة', 'value' => Subject::find($request->subject_id)->name];
            $query->where('subject_id', $request->subject_id);
            $presentAttendance->whereHas('finishedSession', function ($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }

        if ($request->has('month')) {
            $filters[] = ['label' => 'الشهر', 'value' => $request->month];
            $query->whereMonth('created_at', $request->month);
            $presentAttendance->whereHas('finishedSession', function ($q) use ($request) {
                $q->whereMonth('created_at', $request->month);
            });
        }

        $finished_sessions = $query->get();

        $attendance = [];
        foreach ($finished_sessions as $session) {
            foreach ($studentEnrollments as $enrollment) {
                $status = $session->attendances()->where('student_id', $enrollment->student_id)->first()->status;
                $attendance[] = [
                    'date' => $session->created_at->format('Y-m-d'),
                    'time' => $session->created_at->format('H:i'),
                    'studentName' => $enrollment->student->name,
                    'teacher' => $session->staff->name,
                    'subject' => $session->subject->name,
                    'status' => $status === 'present' ? 'حاضر' : ($status === 'absent' ? 'غائب' : 'عذر'),
                ];
            }
        }

        // dd($attendance);

        $presentAttendanceCount = (float) $presentAttendance->count();

        $finishedSessionsCount = (float) $finished_sessions->count();
        $studentCount = (float) $section->student_enrollments()
            ->count();

        $tot = $finishedSessionsCount * $studentCount;
        $avgAttendance = [
            ['label' => 'نسبة الحضور', 'value' => $tot > 0 ? (($presentAttendanceCount / $tot) * 100) . '%' : 0 . '%'],
            ['label' => 'عدد الطلاب', 'value' => count($studentEnrollments)]
        ];

        $year = $section->academic_year;

        return view('grades_sections.sections.PDF.attendance_record', [
            'year' => $year,
            'filters' => $filters,
            'theSection' => $section,
            'attendance' => $attendance,
            'avgAttendance' => $avgAttendance,
            'finishedSessionsCount' => $finishedSessionsCount
        ]);
    }
}
