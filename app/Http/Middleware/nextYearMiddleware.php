<?php

namespace App\Http\Middleware;

use App\Models\AcademicYear;
use App\Models\Section;
use Closure;
use Illuminate\Console\View\Components\Secret;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class nextYearMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $sectionID = $request->route('sectionID');

        $thisYear = Section::findOrFail($sectionID)->academic_year;

        $nextYear = AcademicYear::where('start_date', '>', $thisYear->start_date)
            ->orderBy('start_date', 'asc')->first();

        if (!$nextYear) {
            return redirect()->route('passFailLists.index')->with('error', 'يجب إضافة السنة الدراسية التالية اولاً')->with('alert', 'لمراجعة طريقة تنفيذ هذه العملية الذهاب لقسم دليل الخدمات');
        }

        return $next($request);
    }
}
