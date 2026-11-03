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


        $attendances = $query->get();

        $countOfPresent = $attendances->where('status', 'present')->count();
        $countOfExcused = $attendances->where('status', 'excused')->count();
        $countOfAbsent = $attendances->where('status', 'absent')->count();
        $countOfAttendances = $countOfPresent + $countOfExcused + $countOfAbsent;
        $rateOfAttendances = $countOfAttendances > 0 ? (($countOfPresent + $countOfExcused) / $countOfAttendances) * 100 : 0;

        return view('students/attendance_records/attendance_record', [
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
        ]);

        if ($request->has('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $finished_sessions = $query->get();

        $subjectIDs = $section->finished_sessions
            ->unique('subject_id')
            ->pluck('subject_id')
            ->toArray();
        $subjects = Subject::whereIn('id', $subjectIDs)->get();

        $presentAttendanceCount = (float) Attendance::where('section_id', $sectionID)
            ->where('status', 'present')
            ->count();
        $finishedSessionsCount = (float) $finished_sessions->count();
        $studentCount = (float) $section->student_enrollments()
            ->count();

        $tot = $finishedSessionsCount * $studentCount;
        $avgAttendance = $tot > 0 ? ($presentAttendanceCount / $tot) * 100 : 0;

        return view('grades_sections/sections/attendance_records/attendance_record', [
            'theSection' => $section,
            'finished_sessions' => $finished_sessions,
            'subjects' => $subjects,
            'avgAttendance' => $avgAttendance,
        ]);
    }
}
