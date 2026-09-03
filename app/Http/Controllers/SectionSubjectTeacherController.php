<?php

namespace App\Http\Controllers;

use App\Http\Requests\SessionRequest;
use App\Models\Appointment;
use App\Models\Contract;
use App\Models\File;
use App\Models\Grade;
use App\Models\Section;
use App\Models\SectionSubjectTeacher;
use App\Models\SpatieModelHasRole;
use App\Models\Staff;
use App\Models\Subject;
use App\Models\User;
use Exception;
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
    public function create($sectionID)
    {
        $theSection = Section::findOrFail($sectionID);
        $subjects = Subject::with('teachers')->get();

        return view('study_schedules.create', [
            'subjects' => $subjects,
            'theSection' => $theSection,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SessionRequest $request, $sectionID)
    {
        // dd($request->all());
        // dd($request->validated()['day']);
        try {
            $validated = $request->validated();

            $newAppointment =  Appointment::create([
                'Day' => $validated['day'],
                'start_time' => $validated['start_time'],
                'end_time' =>  $validated['end_time'],
                'status' => 'scheduled',
            ]);

            $session = [
                'section_id' => $sectionID,
                'grade_id' => Section::findOrFail($sectionID)->grade->id,
                'appointment_id' => $newAppointment->id,
            ];

            // مادة جديدة
            $subjectID = $validated['subject'];
            if ($subjectID === 'NEW') {
                $subject = [
                    "new_subject_name" => $validated['new_subject_name'],
                    "new_subject_e_name" => $validated['new_subject_e_name'] ?? null,
                    "new_subject_description" => $validated['new_subject_description'] ?? null,
                ];

                $newSubject = Subject::create($subject);
                $subjectID = $newSubject->id;
            }
            $session['subject_id'] = $subjectID;

            //مدرس جديد
            $staffID = $validated['staff'];
            if ($staffID === 'NEW') {
                $staff = [
                    'name' => $validated['new_staff_name'],
                    'e_name' => $validated['new_staff_e_name'],
                    'phone' => $validated['new_staff_phone'],
                    'email' => $validated['new_staff_email'],
                    'date_of_birth' => $validated['new_staff_date_of_birth'],
                    'gender' => $validated['new_staff_gender'],
                    'is_active' => true,
                    'subject_id' => $subjectID,
                    'staff_type' => 'teacher',
                ];

                $staffPhoto = null;
                if ($request->hasFile('new_staff_img')) {
                    $path = $request->file('new_staff_img')->store('staff_pictures', 'public');
                    $staffPhoto = $path;
                }
                $staff['picture'] = $staffPhoto;

                //انشاء حساب
                $userID = null;
                if ($request->has('new_staff_create_account') && $validated['new_staff_create_account'] === 'on') {
                    $user = [
                        'name' => $validated['new_staff_name'],
                        'email' => $validated['new_staff_email'],
                        'password' => $validated['new_staff_password'],
                        'is_active' => true,
                    ];

                    $newUser = User::create($user);
                    $userID = $newUser->id;

                    SpatieModelHasRole::create([
                        'role_id' => 3,
                        'model_type' => User::class,
                        'model_id' => $userID,
                    ]);
                }
                $staff['user_id'] = $userID;

                //  "new_staff_contract_file" => Illuminate\Http\UploadedFile{#1435 ▶}
                //   "" => "321312"


                $newStaff = Staff::create($staff);
                $staffID = $newStaff->id;

                $contractPath = null;
                if ($request->hasFile('new_staff_contract_file')) {
                    $contractPath = $request->file('new_staff_contract_file')->store('contracts', 'public');

                    Contract::create([
                        'staff_id' => $staffID,
                        'salary' => $validated['new_staff_salary'],
                        'contract_file' => $contractPath,
                        'is_active' => true,
                    ]);
                }
            }
            $session['teacher_id'] = $staffID;

            // dd($session);
            SectionSubjectTeacher::create($session);

            return redirect()->route('studySchedules.index', $sectionID)->with('success', 'تم أضافة الحصة الدرسية بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('studySchedules.index', $sectionID)->with('error', 'حدث خطأ أثناء أضافة الحصة الدرسية: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, $sectionID)
    {
        return view('study_schedules.show', [
            'theSession' => SectionSubjectTeacher::findOrFail($id),
            'theSection' => Section::findOrFail($sectionID),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, $sectionID)
    {
        $theSession = SectionSubjectTeacher::findOrFail($id);
        $theSection = Section::findOrFail($sectionID);
        $subjects = Subject::with('teachers')->get();
        // dd($theSession->appointment->day);
        return view('study_schedules.edit', [
            'subjects' => $subjects,
            'theSection' => $theSection,
            'theSession' => $theSession,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SessionRequest $request, string $id, $sectionID)
    {
        // dd($request->all());
        try {
            $validated = $request->validated();

            $theSession = SectionSubjectTeacher::findOrFail($id);

            $newAppointment =  Appointment::create([
                'Day' => $validated['day'],
                'start_time' => $validated['start_time'],
                'end_time' =>  $validated['end_time'],
                'status' => 'scheduled',
            ]);

            $session = [
                'section_id' => $sectionID,
                'grade_id' => Section::findOrFail($sectionID)->grade->id,
                'appointment_id' => $newAppointment->id,
            ];

            // مادة جديدة
            $subjectID = $validated['subject'];
            if ($subjectID === 'NEW') {
                $subject = [
                    "new_subject_name" => $validated['new_subject_name'],
                    "new_subject_e_name" => $validated['new_subject_e_name'] ?? null,
                    "new_subject_description" => $validated['new_subject_description'] ?? null,
                ];

                $newSubject = Subject::create($subject);
                $subjectID = $newSubject->id;
            }
            $session['subject_id'] = $subjectID;

            //مدرس جديد
            $staffID = $validated['staff'];
            if ($staffID === 'NEW') {
                $staff = [
                    'name' => $validated['new_staff_name'],
                    'e_name' => $validated['new_staff_e_name'],
                    'phone' => $validated['new_staff_phone'],
                    'email' => $validated['new_staff_email'],
                    'date_of_birth' => $validated['new_staff_date_of_birth'],
                    'gender' => $validated['new_staff_gender'],
                    'is_active' => true,
                    'subject_id' => $subjectID,
                    'staff_type' => 'teacher',
                ];

                $staffPhoto = null;
                if ($request->hasFile('new_staff_img')) {
                    $path = $request->file('new_staff_img')->store('staff_pictures', 'public');
                    $staffPhoto = $path;
                }
                $staff['picture'] = $staffPhoto;

                //انشاء حساب
                $userID = null;
                if ($request->has('new_staff_create_account') && $validated['new_staff_create_account'] === 'on') {
                    $user = [
                        'name' => $validated['new_staff_name'],
                        'email' => $validated['new_staff_email'],
                        'password' => $validated['new_staff_password'],
                        'is_active' => true,
                    ];

                    $newUser = User::create($user);
                    $userID = $newUser->id;

                    SpatieModelHasRole::create([
                        'role_id' => 3,
                        'model_type' => User::class,
                        'model_id' => $userID,
                    ]);
                }
                $staff['user_id'] = $userID;

                //  "new_staff_contract_file" => Illuminate\Http\UploadedFile{#1435 ▶}
                //   "" => "321312"


                $newStaff = Staff::create($staff);
                $staffID = $newStaff->id;

                $contractPath = null;
                if ($request->hasFile('new_staff_contract_file')) {
                    $contractPath = $request->file('new_staff_contract_file')->store('contracts', 'public');

                    Contract::create([
                        'staff_id' => $staffID,
                        'salary' => $validated['new_staff_salary'],
                        'contract_file' => $contractPath,
                        'is_active' => true,
                    ]);
                }
            }
            $session['teacher_id'] = $staffID;

            // dd($session);
            $theSession->update($session);

            return redirect()->route('studySchedules.index', $sectionID)->with('success', 'تم تعديل الحصة الدرسية بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('studySchedules.index', $sectionID)->with('error', 'حدث خطأ أثناء تعديل الحصة الدرسية: ' . $e->getMessage());
        }
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


    public function addFile($staffID)
    {
        $theStaff = Staff::findOrFail($staffID);

        return view('staff_members.files', [
            'theStaff' => $theStaff,
        ]);
    }

    public function saveFile(Request $request, $staffID)
    {
        // dd($request->all());

        $val = $request->validate([
            'files' => 'required|array',
            'files.*' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:20480',
        ]);

        // dd($val['files']);

        try {
            if ($val['files']) {
                foreach ($val['files'] as $file) {
                    $path = $file->store('staff_files', 'public');

                    File::create([
                        'name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'uploaded_by' => Auth::id(),
                        'created_at' => now(),
                        'updated_at' => now(),
                        'owner_type' => Staff::class,
                        'owner_id' => $staffID,
                    ]);
                }
            }

            return redirect()->route('staff_members.show', $staffID)->with('success', 'تم رفع الملفات بنجاح');
        } catch (Exception $e) {
            return redirect()->route('staff_members.show', $staffID)->with('error', 'حدث خطأ أثناء رفع الملفات: ' . $e->getMessage());
        }
    }
}
