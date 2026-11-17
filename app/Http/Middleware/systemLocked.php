<?php

namespace App\Http\Middleware;

use App\Models\System;
use App\Models\User;
use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class systemLocked
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $louked = System::where('key', 'system_locked')->first()->value;

        if (!$louked) {
            return $next($request);
        }

        $user = Auth::user() ?? User::where('email', $request->email)->first();
        if (!$user) {
            return redirect()->route('login')->with('error', 'لا يوجد حساب بهذا البريد الإلكتروني');
        }

        if ($user->staff->staff_type == 'teacher') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('error', 'النظام مقفول مؤقتاً من قبل الإدارة');
        }

        if (
            $user->staff->staff_type == 'admin'
            && (
                $request->routeIs('storeLogIn')
                || $request->routeIs('emailVarification')
                || $request->routeIs('sendVerificationCode')
                || $request->routeIs('VerificationCode')
                || $request->routeIs('checkVerificationCode')
                || $request->routeIs('forgotPassword')
                || $request->routeIs('forgotPasswordStore')
                || $request->routeIs('account.create')
                || $request->routeIs('account.store')
            )
        ) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('error', 'النظام مقفول مؤقتاً من قبل الإدارة');
        }

         if (
            $user->staff->staff_type == 'admin'
            && $request->routeIs('logout')
        ) {
            return redirect()->back()->with('error', 'لا يمكن تسجيل الخروج حاليا,النظام مقفول مؤقتاً من قبل الإدارة');
        }

        return $next($request);
    }
}
