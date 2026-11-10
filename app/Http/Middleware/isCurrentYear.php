<?php

namespace App\Http\Middleware;

use App\Models\AcademicYear;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isCurrentYear
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentYear = AcademicYear::where('is_current', true)->first();

        // if (!$currentYear && AcademicYear::count() > 0) {
        //     return redirect()->back()->with('error', 'أنت في إحدى سنين سابقة وليس لديك سوى صلاحيات العرض في هذه السنة');
        // }

        $sessionYearID = session('newCurrentYearID') ?? $currentYear?->id;

        if ($sessionYearID != $currentYear?->id) {
            return redirect()->back()->with('error', 'أنت في إحدى سنين سابقة وليس لديك سوى صلاحيات العرض في هذه السنة');
        }

        return $next($request);
    }
}
