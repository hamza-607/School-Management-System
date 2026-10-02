<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Semester;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    public function index()
    {
        $academicYears = AcademicYear::with('semesters')->get();

        return view('academicYears.index', [
            'academicYears' => $academicYears
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
            ]);

            AcademicYear::create([
                'name' => $validated['name'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'is_current' => 0,
            ]);

            return redirect()->route('academicYears.index')->with('success', 'تم إضافة السنة الدراسية بنجاح.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إضافة السنة الدراسية: ' . $e->getMessage());
        }
    }

    public function storeSemester(Request $request)
    {
        // dd($request->all());

        try {
            $validated = $request->validate([
                'academic_year_id' => 'required|exists:academic_years,id',
                'name' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
            ]);

            Semester::create([
                'academic_year_id' => $validated['academic_year_id'],
                'name' => $validated['name'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'is_current' => null,
            ]);

            return redirect()->route('academicYears.index')->with('success', 'تم إضافة الفصل الدراسي بنجاح.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إضافة الفصل الدراسي: ' . $e->getMessage());
        }
    }
}
