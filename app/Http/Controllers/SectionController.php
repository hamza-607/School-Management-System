<?php

namespace App\Http\Controllers;

use App\Http\Requests\SectionRequest;
use App\Models\AcademicYear;
use App\Models\File;
use App\Models\Grade;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        // dd($request->all());
        $academicYearID = session('newCurrentYearID') ?? AcademicYear::where('is_current', true)->first()->id;

        $query = Section::query();

        $query->where('academic_year_id', $academicYearID);

        if ($request->has('grade_id') && !empty($request->grade_id)) {
            $query->where('grade_id', $request->grade_id);
        }

        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $sections = $query->latest()->paginate($request->per_page ?? 10)->withQueryString();
        $grades = Grade::all();

        return view('grades_sections.sections.index', [
            'sections' => $sections,
            'grades' => $grades,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $grades = Grade::all();

        return view('grades_sections.sections.create', [
            'grades' => $grades,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SectionRequest $request)
    {

        try {
            $validated = $request->validated();
            $academicYearID = AcademicYear::where('is_current', true)->first()->id;

            // dd($validated);
            $section = [
                'name' => $validated['name'],
                'capacity' => $validated['capacity'] ?? 30,
                'academic_year_id' => $academicYearID,
            ];

            if ($request->has('grade') && $validated['grade'] === 'NEW') {

                $lastGrade = Grade::orderByDesc('id')->first();

                $newGrade = Grade::create([
                    'name' => $validated['new_grade_name'],
                ]);

                if ($lastGrade) {
                    $lastGrade->update([
                        'next_grade_id' => $newGrade->id,
                    ]);
                }

                $section['grade_id'] = $newGrade->id;
            } else {
                $section['grade_id'] = $validated['grade'];
            }

            Section::create($section);

            return redirect()->route('sections.index')->with('success', 'تم إضافة الشعبة بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('sections.index')->with('error', 'حدث خطأ أثناء أضافة الشعبة: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $academicYearID = session('newCurrentYearID') ?? AcademicYear::where('is_current', true)->first()->id;
        $theSection = Section::with([
            'student_enrollments' => function ($q) use ($academicYearID) {
                $q->where('student_enrollments.academic_year_id', $academicYearID);
            },
            'student_enrollments.student'
        ])->findOrFail($id);
        // $theSection = Section::findOrFail($id)->student_enrollments()->with('student')->get();
// dd($theSection);
        return view('grades_sections.sections.show', [
            'theSection' => $theSection,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $theSection = Section::findOrFail($id);
        $grades = Grade::all();

        return view('grades_sections.sections.edit', [
            'theSection' => $theSection,
            'grades' => $grades,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SectionRequest $request, string $id)
    {
        try {
            $validated = $request->validated();
            $academicYearID = AcademicYear::where('is_current', true)->first()->id;

            $section = Section::findOrFail($id);
            // dd($validated);
            $newSection = [
                'name' => $validated['name'],
                'capacity' => $validated['capacity'] ?? 30,
                'academic_year_id' => $academicYearID,
            ];

            if ($request->has('grade') && $validated['grade'] === 'NEW') {
                $lastGrade = Grade::orderByDesc('id')->first();

                $newGrade = Grade::create([
                    'name' => $validated['new_grade_name'],
                ]);

                if ($lastGrade) {
                    $lastGrade->update([
                        'next_grade_id' => $newGrade->id,
                    ]);
                }

                $newSection['grade_id'] = $newGrade->id;
            } else {
                $newSection['grade_id'] = $validated['grade'];
            }

            $section->update($newSection);

            return redirect()->route('sections.index')->with('success', 'تم تعديل الشعبة بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('sections.index')->with('error', 'حدث خطأ أثناء تعديل الشعبة: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $section = Section::findOrFail($id);
            // dd($section->files);
            $sectionFiles = $section->files;

            if ($sectionFiles) {
                foreach ($sectionFiles as $file) {
                    Storage::disk('public')->delete($file->file_path);
                    $file->delete();
                }
            }
            $section->delete();

            return redirect()->route('sections.index')->with('success', 'تم حذف الشعبة بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('sections.index')->with('error', 'حدث خطأ أثناء حذف الشعبة: ' . $e->getMessage());
        }
    }

    public function addFile($sectionID)
    {
        $theSection = Section::findOrFail($sectionID);

        return view('grades_sections.sections.files', [
            'theSection' => $theSection,
        ]);
    }

    public function saveFile(Request $request, $sectionID)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:20480',
        ]);

        try {

            foreach ($request->file('files') as $file) {
                $path = $file->store('sections_files', 'public');

                File::create([
                    'name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'uploaded_by' => Auth::id(), // Auth::id()
                    'created_at' => now(),
                    'updated_at' => now(),
                    'owner_type' => Section::class,
                    'owner_id' => $sectionID,
                ]);
            }

            return redirect()->route('sections.show', $sectionID)->with('success', 'تم إضافة الملف بنجاح');
        } catch (\Exception $e) {
            return redirect()->route('sections.show', $sectionID)->with('error', 'حدث خطأ أثناء إضافة الملف' . $e->getMessage());
        }
    }
}
