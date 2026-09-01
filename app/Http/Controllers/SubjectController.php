<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubjectRequest;
use App\Models\File;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // dd($request->all());
        $query = Subject::query();

        if ($request->has('search') && $request->search !== null) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('e_name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $subjects = $query->latest()->paginate($request->per_page)->withQueryString();

        return view('subjects.index', [
            'subjects' => $subjects,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('subjects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubjectRequest $request)
    {
        // dd($request->all());
        $validate = $request->validated();

        // dd($validated);

        try {
            Subject::create([
                'name' => $validate['name'],
                'e_name' => $validate['e_name'] ?? null,
                'description' => $validate['description'] ?? null,
                'is_active' => true,
            ]);

            return redirect()->route('subjects.index')->with('success', 'تم إنشاء المادة بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('subjects.index')->with('error', 'حدث خطأ أثناء إنشاء المادة' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $theSubject = Subject::findOrFail($id);

        return view('subjects.show', [
            'theSubject' => $theSubject,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $theSubject = Subject::findOrFail($id);

        return view('subjects.edit', [
            'theSubject' => $theSubject,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreSubjectRequest $request, string $id)
    {
        $validate = $request->validated();

        try {
            // Subject::create($validate);

            $theSubject = Subject::findOrFail($id);
            $theSubject->update([
                'name' => $validate['name'],
                'e_name' => $validate['e_name'] ?? null,
                'description' => $validate['description'] ?? null,
                'is_active' => $validate['is_active'] ?? 1,
            ]);

            return redirect()->route('subjects.index')->with('success', 'تم تعديل معلومات المادة بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('subjects.index')->with('error', 'حدث خطأ أثناء تعديل معلومات المادة' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $subject = Subject::findOrFail($id);

            $subject->delete();

            return redirect()->route('subjects.index')->with('success', 'تم حذف المادة بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('subjects.index')->with('error', 'حدث خطأ أثناء حذف المادة' . $e->getMessage());
        }
    }

    public function toggle($subjectID)
    {
        try {
            // dd($subjectID);
            // dd(Subject::find(1));
            $subject = Subject::findOrFail($subjectID);
            // dd($subject);

            $subject->update([
                'is_active' => !$subject->is_active,
            ]);

            return redirect(url()->previous())->with('success', 'تم تحديث حالة المادة بنجاح');
        } catch (\Exception $e) {
            return redirect(url()->previous())->with('error', 'حدث خطأ أثناء تحديث حالة المادة' . $e->getMessage());
        }
    }

    public function addFile($subjectID)
    {
        // dd($subjectID);
        $theSubject = Subject::findOrFail($subjectID);

        return view('subjects.files', [
            'theSubject' => $theSubject,
        ]);
    }

    public function saveFile(Request $request, $subjectID)
    {
        // dd($request->file('files'));
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:20480',
        ]);

        try {

            foreach ($request->file('files') as $index => $file) {
                // dd($file->getClientOriginalName());
                $path = $file->store('subjects_files', 'public');

                File::create([
                    'name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'uploaded_by' => Auth::id(), // Auth::id()
                    'created_at' => now(),
                    'updated_at' => now(),
                    'owner_type' => 'App\Models\Subject',
                    'owner_id' => $subjectID,
                ]);
            }

            return redirect()->route('subjects.show', $subjectID)->with('success', 'تم إضافة الملف بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('subjects.show', $subjectID)->with('error', 'حدث خطأ أثناء إضافة الملف' . $e->getMessage());
        }


        // // dd($request->all());
        // $theSubject = Subject::findOrFail($subjectID);

        // $request->validate([
        //     'file' => 'required|file|max:2048', // Adjust max file size as needed
        // ]);

        // try {
        //     $file = $request->file('file');
        //     $filePath = $file->store('subject_files', 'public');

        //     $theSubject->files()->create([
        //         'filename' => $file->getClientOriginalName(),
        //         'filepath' => $filePath,
        //     ]);

        //     return redirect(url()->previous())->with('success', 'تم إضافة الملف بنجاح');
        // } catch (\Exception $e) {
        //      return redirect(url()->previous())->with('error', 'حدث خطأ أثناء إضافة الملف' . $e->getMessage());
        // }
    }
}
