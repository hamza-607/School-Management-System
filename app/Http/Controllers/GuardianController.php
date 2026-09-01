<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGuardianRequest;
use App\Models\File;
use App\Models\Guardian;
use App\Models\Student_parent;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuardianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // dd($request->all());
        $query = Guardian::query();

        if ($request->has('search') && $request->search !== null) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('e_name', 'like', '%' . $request->search . '%')
                    ->orWhere('address', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('gender') && $request->gender !== null) {
            $query->where('gender', $request->gender);
        }

        if ($request->has('relationship') && $request->relationship !== null) {
            $query->whereHas('student_parents', function ($q) use ($request) {
                $q->where('relationship_to_student', $request->relationship);
            });
        }

        $guardians = $query->latest()->paginate($request->per_page)->withQueryString();

        $relationships = Student_parent::all()->pluck('relationship_to_student')->unique();


        return view('guardians.index', [
            'guardians' => $guardians,
            'relationships' => $relationships,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('guardians.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGuardianRequest $request)
    {
        // dd($request->validated());
        $validated = $request->validated();

        try {
            Guardian::create([
                "name" => $validated['name'],
                "e_name" => $validated['e_name'],
                "phone" => $validated['phone'],
                "email" => $validated['email'],
                "address" => $validated['address'],
                "date_of_birth" => $validated['date_of_birth'],
                "gender" => $validated['gender'],
            ]);

            return redirect()->route('guardians.index')->with('success', 'تم إضافة ولي الامر بنجاح');
        } catch (Exception $e) {
            return redirect()->route('guardians.index')->with('error', 'حدث خطأ أثناء إضافة ولي الامر' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $theGuardian = Guardian::findOrFail($id);

        return view('guardians.show', [
            'theGuardian' => $theGuardian,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $theGuardian = Guardian::findOrFail($id);

        return view('guardians.edit', [
            'theGuardian' => $theGuardian,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreGuardianRequest $request, string $id)
    {
        $validated = $request->validated();

        try {
            $theGuardian = Guardian::findOrFail($id);

            $theGuardian->update([
                "name" => $validated['name'],
                "e_name" => $validated['e_name'],
                "phone" => $validated['phone'],
                "email" => $validated['email'],
                "address" => $validated['address'],
                "date_of_birth" => $validated['date_of_birth'],
                "gender" => $validated['gender'],
            ]);

            return redirect()->route('guardians.index')->with('success', 'تم تعديل ولي الامر بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('guardians.index')->with('error', 'حدث خطأ أثناء تعديل ولي الامر' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $guardian = Guardian::findOrFail($id);

            $guardian->delete();

            return redirect(url()->previous())->with('success', 'تم حذف ولي الامر بنجاح');
        } catch (\Exception $e) {
            return redirect(url()->previous())->with('error', 'حدث خطأ أثناء حذف ولي الأمر' . $e->getMessage());
        }
    }

    public function addFile($guardianID)
    {
        $theGuardian = Guardian::findOrFail($guardianID);

        return view('guardians.files', [
            'theGuardian' => $theGuardian,
        ]);
    }

    public function saveFile(Request $request, $guardianID)
    {
        // dd($request->all());

        $val = $request->validate([
            'files' => 'required|array',
            'files.*' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:20480',
        ]);

        // dd($val['files']);

        try {
            foreach ($val['files'] as $file) {
                $path = $file->store('guardian_files', 'public');

                File::create([
                    'name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'uploaded_by' => Auth::id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                    'owner_type' => 'App\Models\Guardian',
                    'owner_id' => $guardianID,
                ]);
            }

            return redirect()->route('guardians.show', $guardianID)->with('success', 'تم رفع الملفات بنجاح');
        } catch (Exception $e) {
            return redirect()->route('guardians.show', $guardianID)->with('error', 'حدث خطأ أثناء رفع الملفات: ' . $e->getMessage());
        }
    }
}
