<?php

namespace App\Http\Controllers;

use App\Http\Requests\FinishedSessionRequest;
use App\Models\Attendance;
use App\Models\FinishedSession;
use App\Models\Grade;
use App\Models\Section;
use App\Models\SectionSubjectTeacher;
use App\Models\Staff;
use App\Models\Student_penalties;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinishedSessionController extends Controller
{

    public function index(Request $request)
    {
        // dd($request->all());
        $query = FinishedSession::query();

        $query->with('staff', 'grade', 'subject', 'section');

        if ($request->has('grade_id') && $request->grade_id !== null) {
            $query->where('grade_id', $request->grade_id);
        }

        if ($request->has('section_id') && $request->section_id !== null) {
            $query->where('section_id', $request->section_id);
        }

        if ($request->has('teacher_id') && $request->teacher_id !== null) {
            $query->where('teacher_id', $request->teacher_id);
        }

        $finishedSessions = $query->latest()->paginate($request->per_page ?? 10);

        $grades = Grade::with('sections')->get();
        $teachers = Staff::where('staff_type', 'teacher')->get();

        return view('study_schedules.finishedSessions.index', [
            'finishedSessions' => $finishedSessions,
            'grades' => $grades,
            'teachers' => $teachers,
        ]);
    }
    // public function show(string $id)
    // {
    //     $theSession = FinishedSession::with(['sectionSubjectTeacher.subject', 'sectionSubjectTeacher.staff', 'sectionSubjectTeacher.appointment', 'sectionSubjectTeacher.section'])->findOrFail($id);

    //     // dd($request->from);

    //     return view('study_schedules.finishedSessions.show', [
    //         'theSession' => $theSession,
    //     ]);
    // }
    public function finish(FinishedSessionRequest $request, $sectionID, $sessionID)
    {
        // dd($request->all());
        // dd('ggag');
        try {
            $validated = $request->validated();
            $session = SectionSubjectTeacher::findOrFail($sessionID);
            // dd($session);
            $finishedSession = FinishedSession::where('appointment_id', $session->appointment_id)
                ->where('teacher_id', $session->teacher_id)
                ->where('subject_id', $session->subject_id)
                ->where('section_id', $session->section_id)
                ->where('grade_id', $session->grade_id)
                ->where('status', 'active')
                ->first();

            // dd($finishedSession);
            $finishedSession->update([
                'actual_end_time' => now()->format('H:i:s'),
                'status' => 'completed',
            ]);

            if ($session->type === 'makeup') {
                $session->delete();
            } else {
                $session->appointment->update(['status' => 'scheduled']);
            }

            //تسجيل الحضور
            if ($request->has('attendance') && $validated['attendance'] !== null) {
                foreach ($validated['attendance'] as $attendance) {
                    Attendance::create([
                        'student_id' => $attendance['student_id'],
                        'finished_session_id' => $finishedSession->id, // بعد ما تعمل الحصة المنهية حط ال id هون
                        'section_id' => $sectionID,
                        'status' => $attendance['status'],
                    ]);
                }
            }

            //تسجيل العقوبات
            if ($request->has('penalties') && $validated['penalties'] !== null) {
                foreach ($validated['penalties'] as $penalty) {
                    Student_penalties::create([
                        'penalty_type' => $penalty['penalty_type'],
                        'reason' => $penalty['reason'],
                        'user_id' => Auth::id(),
                        'updated_by' => null,
                        'status' => 'pending',
                        'notes' => $penalty['notes'] ?? null,
                        'student_id' => $penalty['student_id'],
                    ]);
                }
            }

            return redirect()->route('studySchedules.superIndex')->with('success', 'تم انهاء الجلسة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'حدث خطأ اثناء انهاء الجلسة' . $e->getMessage());
        }
    }
    public function destroy($sessionID)
    {
        try {
            $theSession = FinishedSession::findOrFail($sessionID);
            $theSession->delete();

            return redirect()->route('finishedSessions.index')->with('success', 'تم حذف الجلسة بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'حدث خطأ اثناء حذف الجلسة' . $e->getMessage());
        }
    }
}
