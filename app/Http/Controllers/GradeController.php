<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\BinaryOp\Greater;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Grade::query();
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $grades = $query->paginate($request->per_page)->withQueryString();

        return view('grades_sections.grades.index', [
            'grades' => $grades,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('grades_sections.grades.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100'
            ]);

            Grade::create([
                'name' => $validated['name'],
            ]);

            return redirect()->route('grades.index')->with('success', 'تم إضافة الصف بنجاح.');
        } catch (\Exception $e) {
            return redirect()->route('grades.index')->with('error', 'حدث خطأ أثناء إضافة صف: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $theGrade = Grade::findOrFail($id);

        return view('grades_sections.grades.show', [
            'theGrade' => $theGrade,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $theGrade = Grade::findOrFail($id);

        return view('grades_sections.grades.edit', [
            'theGreade' => $theGrade,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100'
            ]);

            $grade = Grade::findOrFail($id);

            $grade->update([
                'name' => $validated['name'],
            ]);

            return redirect()->route('grades.index')->with('success', 'تم تعديل الصف بنجاح.');
        } catch (\Exception $e) {
            return redirect()->route('grades.index')->with('error', 'حدث خطأ أثناء تعديل صف: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $grade = Grade::findOrFail($id);

            $grade->delete();

            return redirect(url()->previous())->with('success', 'تم حذف الصف بنجاح.');
        } catch (\Exception $e) {
            return redirect(url()->previous())->with('error', 'حدث خطأ أثناء حذف الصف: ' . $e->getMessage());
        }
    }
}
