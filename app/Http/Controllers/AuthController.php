<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rules\Password;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function LogInPage()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('logIn.logIn');
    }

    public function logIn(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => ['required', Password::min(6)],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if ($user) {
            if ($user->is_active === 0) {
                return back()->withErrors(['error' => 'حسابك غير مفعل حاليا، يرجى التواصل مع الإدارة لتفعيل الحساب']);
            }
        } else {
            return back()->withErrors(['error' => 'الإيميل غير موجود في قاعدة البيانات']);
        }

        if (Auth::attempt($validated)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard')->with('success', 'اهلا بعودتك ' . Auth::user()->name);
        }

        return back()->withErrors(['error' => 'الإيميل أو كلمة السر خاطئة !!']);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function editPassword()
    {
        return view('logIn.editPassword');
    }

    public function editPasswordStore(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'old_password' => 'required',
            'new_password' => [
                'required',
                'confirmed',
                Password::min(6)
            ],
        ], [
            'new_password.confirmed' => 'كلمة السر الجديدة غير متطابقة مع التأكيد.',
        ]);

        $user = Auth::user();
        // dd(Hash::check($request->old_password, $user->password));
        if (Hash::check($request->old_password, $user->password)) {
            // dd('hh');
            $user->update([
                'password' => $validated['new_password'],
            ]);

            $this->logout($request);
            return redirect()->route('login')->with('success', 'تم تعديل كلمة السر بنجاح');
        }
        return back()->withErrors(['error', 'كلمة المرور القديمة التي ادخلتها خاطئة!!']);
    }

    public function emailVarification()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('logIn.emailForgotPassword');
    }

    public function sendVarificationCode(Request $request)
    {
        try {
            if (Auth::check()) {
                return redirect()->route('dashboard');
            }

            $validated = $request->validate([
                'email' => 'required|email',
            ]);

            $user = User::where('email', $validated['email'])->first();

            if (!$user) {
                return back()->withErrors(['error' => 'الإيميل غير موجود في قاعدة البيانات']);
            }

            // dd($user->id);

            if (Cache::get('lock_otp_' . $user->email)) {
                return back()->withErrors(['error' => 'لقد تم إرسال رمز التحقق مسبقًا او انك تجاوزت الحد المسموح لمحاولات التحقق. يرجى الانتظار لمدة دقيقة قبل طلب رمز جديد.']);
            }

            $otp = rand(100000, 999999);

            Cache::put('otp_' . $user->email, $otp, now()->addMinutes(5));
            Cache::put('lock_otp_' . $user->email, true, now()->addMinutes(1));
            Cache::put('otp_counter_' . $user->email, 0, now()->addMinutes(5));

            Mail::raw("رمز التحقق الخاص بك هو : \n{$otp}\nالرمز صالح لمدة 5 دقائق", function ($message) use ($user) {
                $message->to($user->email)->subject('زمر التحقق الخاص بك / تعديل كلمة سر حسابك في مدرسة افق النموذجية');
            });

            return redirect()->route('VarificationCode', $user->id)->with('success', 'تم إرسال رمز التحقق إلى بريدك الإلكتروني. رمز التحقق: '); // هون هي لازم تتغير لصفحة التحقق من الرمز اول شي

        } catch (Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء إرسال رمز التحقق. يرجى المحاولة مرة أخرى.']);
        }
    }

    public function VarificationCode($userID)
    {
        // dd($userID);
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('logIn.VarificationCode', [
            'userID' => $userID,
        ]);
    }

    public function checkVarificationCode(Request $request, $userID)
    {
        try {
            if (Auth::check()) {
                return redirect()->route('dashboard');
            }

            $validated = $request->validate([
                'otp'   => 'required|numeric'
            ]);
            // dd($request->all());

            // dd($userID);
            $user = User::findOrFail($userID);

            // dd($user);
            if (!$user) {
                return redirect()->route('emailVarification')->with('error', 'هذا المستخدم غير موجود حاول مجدداً');
            }

            $email = $user->email;
            $checkOtpTime = Cache::get('otp_' . $email);
            $checkOtpCounter = Cache::get('otp_counter_' . $user->email);

            if (!$checkOtpTime) {
                return redirect()->route('emailVarification')->with('error', ' هذا الرمز قد انتهت صلاحيته. يرجى طلب رمز جديد.');
            }

            if ($checkOtpCounter >= 5) {
                Cache::forget('otp_' . $email);
                Cache::forget('otp_counter_' . $email);
                Cache::put('lock_otp_' . $user->email, true, now()->addMinutes(1));

                return redirect()->route('emailVarification')->with('error', 'لقد تجاوزت الحد الأقصى لمحاولات التحقق. يرجى طلب رمز جديد بعد دقيقة من الآن.');
            }

            // dd($checkOtpCounter);
            if ((int) $checkOtpTime !== (int) $validated['otp']) {
                Cache::increment('otp_counter_' . $email);
                return back()->withErrors(['errors' => 'هذا الرمز غير صحيح. يرجى المحاولة مرة أخرى.']);
            }

            Cache::forget('otp_' . $email);
            Cache::forget('otp_counter_' . $email);
            Cache::forget('lock_otp_' . $user->email);

            return redirect()->route('forgotPassword', $userID)->with('success', 'تم التحقق من الرمز بنجاح. يمكنك الآن تعديل كلمة السر الخاصة بك.');
        } catch (Exception $e) {
            return back()->withErrors(['errors' => 'حدث خطأ أثناء التحقق من الرمز. يرجى المحاولة مرة أخرى.']);
        }
    }

    public function forgotPassword($userID)
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('logIn.forgotPassword', [
            'userID' => $userID,
        ]);
    }

    public function forgotPasswordStore(Request $request, $userID)
    {
        // dd($request->all());
        try {
            if (Auth::check()) {
                return redirect()->route('dashboard');
            }

            $validated = $request->validate([
                'new_password' => [
                    'required',
                    'confirmed',
                    Password::min(6)
                ],
            ], [
                'new_password.confirmed' => 'كلمة السر الجديدة غير متطابقة مع التأكيد.',
            ]);

            // dd($validated);

            $user = User::findOrFail($userID);

            if (!$user) {
                return redirect()->route('login')->with('error', 'هذا المستخدم غير موجود حاول مجدداً');
            }

            $user->update([
                'password' => $validated['new_password'],
            ]);

            return redirect()->route('login')->with('success', 'تم تعديل كلمة السر بنجاح.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء تعديل كلمة السر. يرجى المحاولة مرة أخرى.']);
        }
    }
}
