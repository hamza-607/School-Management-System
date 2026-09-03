<?php

namespace App\Http\Controllers;

use App\Http\Requests\StaffRequest;
use App\Models\Contract;
use App\Models\File;
use App\Models\SpatieModelHasRole;
use App\Models\Staff;
use App\Models\Subject;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // dd($request->all());

        $staffType = $request->from;
        $query = Staff::query();
        // dd($staffType);

        if ($staffType === 'teacher') {
            $query->where('staff_type', 'teacher');
        }

        if ($staffType === 'admin') {
            $query->where('staff_type', 'admin');
        }

        if ($staffType === 'other') {
            $query->whereNotIn('staff_type', ['teacher', 'admin', 'Super Admin']);
        }

        if ($request->has('status') && $request->status !== null) {
            $query->where('is_active', $request->status);
        }

        if ($request->has('gender') && $request->gender !== null) {
            $query->where('gender',  $request->gender);
        }

        if ($request->has('subject') && $request->subject !== null) {
            $query->whereHas('subject', function ($q) use ($request) {
                $q->where('id', $request->subject);
            });
        }

        if ($request->has(('search')) && $request->search !== null) {
            // dd('hi');
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('e_name', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $staffMembers = $query->latest()->paginate($request->per_page)->withQueryString();

        // dd($staffMembers);

        return view('staff_members.index', [
            'staffMembers' => $staffMembers,
            'from' => $staffType,
            'subjects' => Subject::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('staff_members.create', [
            'subjects' => Subject::all(),
            'from' => $request->from ? $request->from : null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StaffRequest $request)
    {
        // dd($request->validated());

        $validated = $request->validated();
        // dd($validated);
        try {
            $staff = [
                'name' => $validated['name'],
                'e_name' => $validated['e_name'] ?? null,
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'date_of_birth' => $validated['date_of_birth'],
                'gender' => $validated['gender'],
                'is_active' => 1,
                'staff_type' => $validated['staff_type'] === 'other' ? $validated['new_staff_type'] : $validated['staff_type'],
            ];
            // dd($staff);

            if ($request->hasFile('img')) {
                $path = $request->file('img')->store('staff_pictures', 'public');
            }
            $staff['picture'] = $path ?? null;
            $valSubject = $validated['subject'] ?? null;
            if ($valSubject !== null && $valSubject === 'NEW') {
                $subject = [
                    'name' => $validated['new_subject_name'],
                    'e_name' => $validated['new_subject_e_name'] ?? null,
                    'description' => $validated['new_subject_description'] ?? null,
                    'is_active' => 1,
                ];

                $newSubject = Subject::create($subject);
                $staff['subject_id'] = $newSubject->id;
            } else {
                $staff['subject_id'] = $validated['subject'] ?? null;
            }

            $acc = $validated['create_account'] ?? null;
            if ($acc !== null && $acc === 'on') {
                $user = [
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => $validated['password'],
                ];

                $newUser = User::create($user);
                $staff['user_id'] = $newUser->id;

                SpatieModelHasRole::create([
                    'role_id' => $validated['staff_type'] === 'teacher' ? 3 : 2,
                    'model_type' => User::class,
                    'model_id' => $newUser->id,
                ]);
            } else {
                $staff['user_id'] = null;
            }
            // dd($staff);
            $newStaff = Staff::create($staff);

            $contractPath = null;
            if ($validated['contract_file']) {
                $contractPath = $validated['contract_file']->store('contracts', 'public');

                Contract::create([
                    'staff_id' => $newStaff->id,
                    'salary' => $validated['salary'] ?? 0,
                    'contract_file' => $contractPath,
                    'is_active' => 1,
                ]);
            }

            return redirect()->route('staff_members.index', ['from' => $validated['staff_type']])->with('success', 'تم إضافة الموظف بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('staff_members.index', ['from' => $validated['staff_type']])->with('error', 'حدث خطأ أثناء إضافة الموظف: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        $theStaff = Staff::findOrFail($id);

        return view('staff_members.show', [
            'theStaff' => $theStaff,
            'from' => $request->from ? $request->from : null,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, Request $request)
    {
        $theStaff = Staff::findOrFail($id);

        return view('staff_members.edit', [
            'theStaff' => $theStaff,
            'from' => $request->from,
            'subjects' => Subject::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StaffRequest $request, string $id)
    {
        // dd($request->all());
        try {
            $validated = $request->validated();

            $theStaff = Staff::find($id);

            $staff = [
                'name' => $validated['name'],
                'e_name' => $validated['e_name'] ?? null,
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'date_of_birth' => $validated['date_of_birth'],
                'gender' => $validated['gender'],
                'is_active' => 1,
                'staff_type' => $validated['staff_type'] === 'other' ? $validated['new_staff_type'] : $validated['staff_type'],
            ];
            // dd($staff);

            //صورة
            if ($request->hasFile('img')) {
                $path = $validated['img']->store('staff_pictures', 'public');
                $staff['picture'] = $path;
            }

            //مادة جديدة
            $valSubject = $validated['subject'] ?? null;
            if ($valSubject !== null && $valSubject === 'NEW') {
                $subject = [
                    'name' => $validated['new_subject_name'],
                    'e_name' => $validated['new_subject_e_name'] ?? null,
                    'description' => $validated['new_subject_description'] ?? null,
                    'is_active' => 1,
                ];

                $newSubject = Subject::create($subject);
                $staff['subject_id'] = $newSubject->id;
            } else {
                $staff['subject_id'] = $validated['subject'] ?? null;
            }

            //تعديل العقد
            $theContract = Contract::findOrFail($theStaff->contract->id);
            if ($request->hasFile('contract_file')) {
                $path = $validated['contract_file']->store('contracts', 'public');

                $theContract->update([
                    'contract_file' => $path,
                ]);
            }
            $theContract->update([
                'salary' => $validated['salary'],
            ]);

            // تعديلات الحساب
            $acc = $validated['create_account'] ?? null;
            if ($acc !== null && $acc === 'on') {
                $user = [
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => $validated['password'],
                ];

                $newUser = User::create($user);
                $staff['user_id'] = $newUser->id;

                SpatieModelHasRole::create([
                    'role_id' => $validated['staff_type'] === 'teacher' ? 3 : 2,
                    'model_type' => User::class,
                    'model_id' => $newUser->id,
                ]);
            } else {
                $theAccount = $theStaff->user ?? null;
                // dd($theAccount);
                if ($theAccount === null) {
                    $staff['user_id'] = null;
                } else {
                    // dd($theAccount);

                    $thePermissions = $theAccount->spatieModelHasRole;
                    // dd($thePermissions);
                    $theAccount->update([
                        'name' => $validated['name'],
                        'email' => $validated['email'],
                    ]);
                    $thePermissions->where(
                        [
                            'model_type' => User::class,
                            'model_id' => $theAccount->id
                        ]
                    )->update([
                        'role_id' => $validated['staff_type'] === 'teacher' ? 3 : 2,
                    ]);
                    // dd('.');
                }
            }

            // dd($staff);
            $theStaff->update($staff);

            return redirect()->route('staff_members.index', ['from' => $validated['staff_type']])->with('success', 'تم تعديل معلومات الموظف بنجاح');
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return redirect()->route('staff_members.index', ['from' => $request->staff_type])->with('error', 'حدث خطأ أثناء تعديل معلومات الموظف: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $staff = Staff::findOrFail($id);
            $user = $staff->user;
            if ($user) {
                $user->delete();
            }
            $staff->delete();

            return redirect(url()->previous())->with('success', 'تم حذف الموظف بنجاح');
        } catch (\Exception $e) {
            return redirect(url()->previous())->with('error', 'حدث خطأ أثناء حذف الموظف: ' . $e->getMessage());
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


    public function toggle($staffID)
    {
        try {
            $staff = Staff::findOrFail($staffID);
            $staff->update(['is_active' => !$staff->is_active]);

            if (!$staff->is_active && $staff->user) {
                $staff->user->update(['is_active' => 0]);
            }

            return redirect(url()->previous())->with('success', 'تم تغيير حالة الموظف بنجاح');
        } catch (\Exception $e) {
            return redirect(url()->previous())->with('error', 'حدث خطأ أثناء تغيير حالة الموظف: ' . $e->getMessage());
        }
    }

    public function userToggle($staffID)
    {
        try {
            $staff = Staff::findOrFail($staffID);
            $user = $staff->user;
            if (!$user) {
                return redirect(url()->previous())->with('error', 'لم يتم العثور على حساب لهذا الموظف.');
            }
            if ($staff->is_active === 0) {
                return redirect(url()->previous())->with('error', 'الموظف غير نشط. يرجى تفعيل الموظف أولًا.');
            }
            // dd(!$user->is_active);
            $user->update(['is_active' => !$user->is_active]);

            return redirect(url()->previous())->with('success', 'تم تغيير حالة حساب الموظف بنجاح');
        } catch (\Exception $e) {
            return redirect(url()->previous())->with('error', 'حدث خطأ أثناء تغيير حالة حساب الموظف: ' . $e->getMessage());
        }
    }
}
