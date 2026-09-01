<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Models\File;
use App\Models\Grade;
use App\Models\Guardian;
use App\Models\OntParent;
use App\Models\Section;
use App\Models\Student;
use App\Models\Student_parent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // dd($request->all());

        $query = Student::query();

        if ($request->has('search') && $request->search !== null) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('e_name', 'like', '%' . $request->search . '%')
                    ->orWhere('address', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('status') && $request->status !== null) {
            $query->where('is_active', $request->status);
        }

        if ($request->has('grade_id') && $request->grade_id !== null) {
            $query->where('grade_id', $request->grade_id);
        }

        if ($request->has('section_id') && $request->section_id !== null) {
            $query->where('section_id', $request->section_id);
        }

        if ($request->has('gender') && $request->gender !== null) {
            $query->where('gender', $request->gender);
        }

        // if($request->has('financial_status') && $request->financial_status !== null) {
        //     $query->where('financial_status', $request->financial_status);
        // }

        $students = $query->latest()->paginate($request->per_page)->withQueryString();
        $gradesWithSections = Grade::with('sections')->get();

        return view('students.index', [
            'students' => $students,
            'grades' => $gradesWithSections,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // dd($request->fromGuardianID);
        $grades = Grade::all();
        $sections = Section::all();

        return view('students.create', [
            'sections' => $sections,
            'grades' => $grades,
            'fromGuardian' => $request->fromGuardianID ? Guardian::findOrFail($request->fromGuardianID) : null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request)
    {
        // dd($request->all());
        // $path = $request->file('img')->store('clinicLogo', 'public');

        // dd($path);
        // dd($request->file('img'));
        $validate = $request->validated();
        try {
            // dd($validate);

            $student = [
                'name' => $validate['name'],
                'e_name' => $validate['e_name'] ?? null,
                'section_id' => $validate['section'] !== 'NEW' ? $validate['section'] : null,
                'grade_id' => $validate['grade'] !== 'NEW' ? $validate['grade'] : null,
                'date_of_birth' => $validate['date_of_birth'],
                'gender' => $validate['gender'],
                'address' => $validate['address'],
                'phoneNumber' => $validate['phone'] ?? null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if ($validate['grade'] === 'NEW') {
                $grade = Grade::create([
                    'name' => $validate['new_grade_name'],
                ]);

                $student['grade_id'] = $grade->id;
            }

            // dd($student);
            if ($validate['section'] === 'NEW') {
                $section = Section::create([
                    'name' => $validate['new_section_name'],
                    'capacity' => $validate['new_section_capacity'] ?? 30,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'grade_id' => $student['grade_id'],
                ]);

                $student['section_id'] = $section->id;
            }

            $path = null;
            if ($request->hasFile('img')) {
                $path = $request->file('img')->store('studentPhoto', 'public');
            }
            $student['picture'] = $path;

            // dd($student);
            $newStudent = Student::create($student);

            if ($request->has('old_parent') && is_array($request->old_parent)) {
                Student_parent::create([
                    'student_id' => $newStudent->id,
                    'parent_id' => $request->old_parent['id'],
                    'relationship_to_student' => $request->old_parent['relationship_to_student'],
                ]);
            }

            if ($request->has('new_parent') && is_array($request->new_parent)) {
                // dd($validate['new_parent']);
                foreach ($validate['new_parent'] as $onePar) {
                    // dd($onePar['relationship_to_student']);
                    $newParent = Guardian::create([
                        'name' => $onePar['name'],
                        'e_name' => $onePar['e_name'],
                        'phone' => $onePar['phone'],
                        'email' => $onePar['email'],
                        'address' => $onePar['address'],
                        'date_of_birth' => $onePar['date_of_birth'],
                        'gender' => $onePar['gender'],
                    ]);

                    Student_parent::create([
                        'student_id' => $newStudent->id,
                        'parent_id' => $newParent->id,
                        'relationship_to_student' => $onePar['relationship_to_student'],
                    ]);
                }
            }

            if ($request->fromGuardianID) {
                return redirect()->route('guardians.index')->with('success', 'تم إضافة الطالب بنجاح');
            } else {
                return redirect()->route('students.index')->with('success', 'تم إضافة الطالب بنجاح');
            }
        } catch (\Exception $e) {
            if ($request->fromGuardianID) {
                return redirect()->route('guardians.index')->with('error', 'حدث خطأ أثناء إضافة الطالب' . $e->getMessage());
            } else {
                return redirect()->route('students.index')->with('error', 'حدث خطأ أثناء إضافة الطالب' . $e->getMessage());
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $theStudent = Student::findOrFail($id);

        return view('students.show', [
            'theStudent' => $theStudent,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $student = Student::findOrFail($id);
        // dd($student);
        $grades = Grade::all();
        $sections = Section::all();
        // dd($sections);
        return view('students.edit', [
            'theStudent' => $student,
            'sections' => $sections,
            'grades' => $grades,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreStudentRequest $request, string $id)
    {
        // dd($request->all());
        $validate = $request->validated();
        // dd(Guardian::whereIn('id', $validate['old_parent_ids'])->pluck('id')->toArray());
        try {
            // dd($validate);

            $student = [
                'name' => $validate['name'],
                'e_name' => $validate['e_name'] ?? null,
                'section_id' => $validate['section'] !== 'NEW' ? $validate['section'] : null,
                'grade_id' => $validate['grade'] !== 'NEW' ? $validate['grade'] : null,
                'date_of_birth' => $validate['date_of_birth'],
                'gender' => $validate['gender'],
                'address' => $validate['address'],
                'phoneNumber' => $validate['phone'] ?? null,
                'is_active' => $validate['is_active'] ?? 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            // $grade_id = $validate['grade'] !== 'NEW' ? $validate['grade'] : null;

            if ($validate['grade'] === 'NEW') {
                $grade = Grade::create([
                    'name' => $validate['new_grade_name'],
                ]);

                $student['grade_id'] = $grade->id;
            }

            // dd($student);
            if ($validate['section'] === 'NEW') {
                $section = Section::create([
                    'name' => $validate['new_section_name'],
                    'capacity' => $validate['new_section_capacity'] ?? 30,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'grade_id' => $student['grade_id'],
                ]);

                $student['section_id'] = $section->id;
            }

            $path = null;
            if ($request->hasFile('img')) {
                $path = $request->file('img')->store('studentPhoto', 'public');
            }
            $student['picture'] = $path;

            // dd($student);
            $theStudent = Student::findOrFail($id);
            $theStudent->update($student);
            // Student::create($student);

            //حذف ولي امر قديم
            if ($request->has('old_parent_ids') && is_array($request->old_parent_ids)) {
                $dataIds = Student_parent::where('student_id', $theStudent->id)->pluck('parent_id')->toArray();
                $requestIds = $validate['old_parent_ids'];
                // dd($dataIds, $requestIds);
                $toDeleteIds = array_diff($dataIds, $requestIds);
                // dd($toDeleteIds);
                Guardian::whereIn('id', $toDeleteIds)->delete();
                Student_parent::whereIn('parent_id', $toDeleteIds)->delete();
            }
            //اضافة او تعديل ولي امر
            if ($request->has('new_parent') && is_array($request->new_parent)) {
                // if($validate['new_parent'][])
                foreach ($validate['new_parent'] as $key => $onePar) {

                    //ولي امر جديد
                    if ($key === 'new') {
                        $newParent = Guardian::create(
                            [
                                'name' => $onePar['name'],
                                'e_name' => $onePar['e_name'],
                                'phone' => $onePar['phone'],
                                'email' => $onePar['email'],
                                'address' => $onePar['address'],
                                'date_of_birth' => $onePar['date_of_birth'],
                                'gender' => $onePar['gender'],
                            ]
                        );

                        Student_parent::create(
                            [
                                'student_id' => $theStudent->id,
                                'parent_id' => $newParent->id,
                                'relationship_to_student' => $onePar['relationship_to_student'],
                            ]
                        );
                    }
                    //ولي امر قديم معدل
                    else {
                        $oldParent = Guardian::findOrFail($onePar['id']);
                        $oldParent->update(
                            [
                                'name' => $onePar['name'],
                                'e_name' => $onePar['e_name'],
                                'relationship_to_student' => $onePar['relationship_to_student'],
                                'phone' => $onePar['phone'],
                                'email' => $onePar['email'],
                                'address' => $onePar['address'],
                                'date_of_birth' => $onePar['date_of_birth'],
                                'gender' => $onePar['gender'],
                            ]
                        );
                        $student_parent = Student_parent::where('student_id', $theStudent->id)
                            ->where('parent_id', $oldParent->id)
                            ->first();

                        $student_parent->update([
                            'relationship_to_student' => $onePar['relationship_to_student'],
                        ]);
                    }
                }
            }


            return redirect()->route('students.index')->with('success', 'تم تعديل بيانات الطالب بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('students.index')->with('error', 'حدث خطأ أثناء تعديل بيانات الطالب' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $student = Student::findOrFail($id);
            $student->delete();

            return redirect()->route('students.index')->with('success', 'تم حذف الطالب بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('students.index')->with('error', 'حدث خطأ أثناء حذف الطالب' . $e->getMessage());
        }
    }

    public function toggle($studentID)
    {
        // dd('gggwdglwrkfpoeqkfp');
        try {
            $student = Student::findOrFail($studentID);

            $student->update([
                'is_active' => !$student->is_active,
            ]);

            return redirect(url()->previous())->with('success', 'تم تعديل حالة الطالب بنجاح');
        } catch (\Exception $e) {
            return redirect(url()->previous())->with('error', 'حدث خطأ أثناء تعديل حالة الطالب' . $e->getMessage());
        }
    }

    public function addFile($studentID)
    {
        $theStudent = Student::findOrFail($studentID);

        return view('students.files', [
            'theStudent' => $theStudent,
        ]);
    }

    public function saveFile(Request $request, $studentID)
    {
        // dd($request->file('files'));
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:20480',
        ]);

        try {

            foreach ($request->file('files') as $file) {
                $path = $file->store('students_files', 'public');

                File::create([
                    'name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'uploaded_by' => Auth::id(), // Auth::id()
                    'created_at' => now(),
                    'updated_at' => now(),
                    'owner_type' => 'App\Models\Student',
                    'owner_id' => $studentID,
                ]);
            }

            return redirect()->route('students.show', $studentID)->with('success', 'تم إضافة الملف بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('students.show', $studentID)->with('error', 'حدث خطأ أثناء إضافة الملف' . $e->getMessage());
        }
    }
}
