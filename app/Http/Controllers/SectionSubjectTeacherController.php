<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Section;
use App\Models\SectionSubjectTeacher;
use App\Models\Staff;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionSubjectTeacherController extends Controller
{

    public function superIndex()
    {
        if (Auth::check()) {
            $teacher_id = Auth::user()->staff->id;
        } else {
            return back()->with('error', 'أنت لم تقم ب عملية تسجيل الدخول');
        }

        // dd($teacher_id);
        $sectionSubjectTeachers = SectionSubjectTeacher::with(['subject', 'staff', 'appointment'])->where('teacher_id', $teacher_id)->get();
        // dd($sectionSubjectTeachers);

        $sections = Section::with('grade')->get();
        // dd($sectionSubjectTeacher);

        return view('study_schedules.superIndex', [
            'sectionSubjectTeachers' => $sectionSubjectTeachers ?? null,
            'sections' => $sections,
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index($sectionID, Request $request)
    {
        // dd($request->all());
        $section = Section::findOrFail($sectionID);
        $gredeID = $section->grade->id;
        $query = SectionSubjectTeacher::query();
        $query->where('section_id', $sectionID)->where('grade_id', $gredeID);

        if ($request->has('day') && $request->day !== null) {
            $query->whereHas('appointment', function ($q) use ($request) {
                $q->where('day', $request->day);
            });
        }

        if ($request->has('status') && $request->status) {
            $query->whereHas('appointment', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        if ($request->has('subject') && $request->subject) {
            $query->where('subject_id', $request->subject);
        }

        $sectionSubjectTeachers = $query->paginate($request->per_page)->withQueryString();
        return view('study_schedules.index', [
            'sectionSubjectTeachers' => $sectionSubjectTeachers,
            'section' => $section,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($sessionID)
    {
        $theSession = Section::findOrFail($sessionID);
        $grades = Grade::all();
        $sections = Section::all();
        $subjects = Subject::with('teachers');

        return view('study_schedules.create', [
            'grades' => $grades,
            'sections' => $sections,
            'subjects' => $subjects,
            'theSession' => $theSession,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $session = SectionSubjectTeacher::findOrFail($id);

            $session->delete();
            $session->appointment->delete();

            return redirect(url()->previous())->with('success', 'تم حذف هذه الجلسة بنجاح');
        } catch (\Exception $e) {
            return redirect(url()->previous())->with('error', 'حدث خطأ أثناء تحديث حالة الجلسة: ' . $e->getMessage());
        }
    }

    public function sessionStatusToggel($sessionID, Request $request)
    {
        try {
            $sessionAppontment = SectionSubjectTeacher::findOrFail($sessionID)->appointment;
            $newStatus = $request->sessionStatus === 'ملغية' ? 'canceled' : ($request->sessionStatus === 'نشطة' ? 'active' : 'scheduled');
            // dd($newStatus);

            $sessionAppontment->update([
                'status' => $newStatus
            ]);

            if ($newStatus === 'active') {
                return redirect(url()->previous())->with('success', 'تم تفعيل الجلسة بنجاح.');
            } elseif ($newStatus === 'canceled') {
                return redirect(url()->previous())->with('success', 'تم إلغاء الجلسة بنجاح.');
            } else {
                return redirect(url()->previous())->with('success', 'تم جدولة الجلسة بنجاح.');
            }
        } catch (\Exception $e) {
            return redirect(url()->previous())->with('error', 'حدث خطأ أثناء تحديث حالة الجلسة: ' . $e->getMessage());
        }
    }
}
