<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Enums\role;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function LogInPage()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('logIn');
    }

    public function editPassword()
    {
        return view('editPassword');
    }

    public function logIn(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => ['required', Password::min(6)],
        ]);

        $user = User::where('email', $validated['email'])->first();
        // dd($user);

        if ($user) {
            if ($user->is_active === 0) {
                return back()->withErrors(['error' => 'حسابك غير مفعل حاليا، يرجى التواصل مع الإدارة لتفعيل الحساب']);
            }
        } else {
            return back()->withErrors(['error' => 'الإيميل المكتوب غير صحيح']);
        }

        if (Auth::attempt($validated)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard')->with('success', 'اهلا بعودتك ' . Auth::user()->name);
        }

        return back()->withErrors(['error' => 'الإيميل أو كلمة السر خاطئة !!']);
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

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
