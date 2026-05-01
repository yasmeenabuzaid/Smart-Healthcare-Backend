<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    // دالة معالجة تسجيل الدخول
    public function login(Request $request)
    {
        // 1. التحقق من صحة المدخلات
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. تحديد نوع المدخل: إيميل أو رقم وطني
        $loginValue = $request->input('email');
        $loginField = filter_var($loginValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'national_number';

        $credentials = [
            $loginField => $loginValue,
            'password'  => $request->input('password'),
        ];

        // 3. محاولة تسجيل الدخول
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // تجديد الجلسة لحماية النظام
            $request->session()->regenerate();

            // التوجيه إلى لوحة التحكم
            return redirect()->intended(route('dashboard'));
        }

        // 4. في حال فشل تسجيل الدخول
        return back()->withErrors([
            'email' => 'البيانات المدخلة غير متطابقة مع سجلاتنا.',
        ])->onlyInput('email');
    }

    // دالة تسجيل الخروج
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/welcome'); // يمكنك تغيير التوجيه إلى '/' إذا أردت
    }
}
