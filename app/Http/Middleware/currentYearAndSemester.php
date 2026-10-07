<?php

namespace App\Http\Middleware;

use App\Models\AcademicYear;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class currentYearAndSemester
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $year = null;
        $semester = null;
        $yearID = session('newCurrentYearID') ?? null;
        $semesterID = session('newCurrentSemesterID') ?? null;
        if ($yearID) {
            $year = AcademicYear::findOrFail($yearID);
        } else {
            $year = AcademicYear::where('is_current', 1)->first();
        }
        if ($semesterID) {
            $semester = $year ? ($year->semesters()->findOrFail($semesterID)) : null;
        } else {
            $semester = $year ? ($year->semesters()->where('is_current', 1)->first()) : null;
        }

        if (!$year) {
            if (Auth::user()->staff->staff_type === 'admin' || Auth::user()->staff->staff_type === 'Super Admin') {
                return redirect()->route('academicYears.index')->with('error', 'لا يوجد سنة دراسية فعالة، يرجى إضافة سنة دراسية حالية أولاً.');
            }
            if (Auth::user()->staff->staff_type === 'teacher') {
                return redirect()->route('dashboard')->with('error', 'لا يوجد سنة دراسية فعالة، يرجى التواصل مع الإدارة لإضافة سنة دراسية حالية.');
            }
        }

        if (!$semester) {
            if (Auth::user()->staff->staff_type === 'admin' || Auth::user()->staff->staff_type === 'Super Admin') {
                return redirect()->route('academicYears.index')->with('error', 'لا يوجد فصل دراسي فعال، يرجى إضافة فصل دراسي حالي أولاً.');
            }
            if (Auth::user()->staff->staff_type === 'teacher') {
                return redirect()->route('dashboard')->with('error', 'لا يوجد فصل دراسي فعال، يرجى التواصل مع الإدارة لإضافة فصل دراسي حالي.');
            }
        }

        return $next($request);
    }
}
