<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    // عرض صفحة التسجيل
    public function showRegistrationForm()
    {
        return view('register');
    }

    // معالجة طلب التسجيل
    public function register(Request $request)
    {
        // 1. التحقق من صحة البيانات (Validation)
        $validated = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'phone'           => ['required', 'string', 'max:20', 'unique:users'],
            'national_number' => ['required', 'string', 'max:20', 'unique:users'],
            'password'        => ['required', 'string', 'min:8', 'confirmed'], // يتطلب حقل password_confirmation
            'role_id'         => ['required', 'integer', 'exists:roles,id', 'not_in:1'], // حماية أمنية: يمنع تسجيل مدير نظام
        ], [
            // رسائل خطأ مخصصة (اختياري)
            'role_id.not_in' => 'لا يمكنك التسجيل بهذه الصلاحية لأسباب أمنية.',
        ]);

        // 2. إنشاء المستخدم وتشفير كلمة المرور
        $user = User::create([
            'name'            => $validated['name'],
            'email'           => $validated['email'],
            'phone'           => $validated['phone'],
            'national_number' => $validated['national_number'],
            'password'        => Hash::make($validated['password']),
            'role_id'         => $validated['role_id'],
        ]);

        // 3. تسجيل الدخول تلقائياً بعد التسجيل
        Auth::login($user);

        // 4. التوجيه بناءً على الصلاحية
        return redirect()->route('dashboard')->with('success', 'تم إنشاء الحساب بنجاح!');
    }
}
