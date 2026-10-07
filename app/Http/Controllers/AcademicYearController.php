<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Semester;
use Illuminate\Http\Request;

use function PHPSTORM_META\type;

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

            return redirect()->route('academicYears.index')->with('success', 'تم إضافة السنة الدراسية بنجاح.')->with('alirt', 'يجب أن يتم تفعيل السنة الدراسية الجديدة من خلال الضغط على زر "تفعيل" بجانب السنة الدراسية الجديدة.');
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
                'is_current' => false,
            ]);

            return redirect()->route('academicYears.index')->with('success', 'تم إضافة الفصل الدراسي بنجاح.')->with('alirt', 'يجب أن يتم تفعيل الفصل الدراسي الجديد من خلال الضغط على زر "تفعيل" بجانب الفصل الدراسي الجديد.');;
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إضافة الفصل الدراسي: ' . $e->getMessage());
        }
    }

    public function setYearCurrent($yearID)
    {
        try {
            $year = AcademicYear::findOrFail($yearID);
            $currentYears = AcademicYear::where('is_current', true)->get();

            foreach ($currentYears as $oneYear) {
                $oneYear->update([
                    'is_current' => false,
                ]);
            }

            $year->update([
                'is_current' => true,
            ]);

            return redirect()->route('academicYears.index')->with('success', 'تم تفعيل السنة الدراسية بنجاح.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تفعيل السنة الدراسية: ' . $e->getMessage());
        }
    }

    public function setSemesterCurrent($semesterID)
    {
        try {
            $semester = Semester::findOrFail($semesterID);
            $currentSemesters = Semester::where('is_current', true)->get();

            foreach ($currentSemesters as $oneSemester) {
                $oneSemester->update([
                    'is_current' => false,
                ]);
            }

            $semester->update([
                'is_current' => true,
            ]);

            return redirect()->route('academicYears.index')->with('success', 'تم تفعيل الفصل الدراسي بنجاح.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تفعيل الفصل الدراسي: ' . $e->getMessage());
        }
    }
}
