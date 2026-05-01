<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>إنشاء حساب - النظام الشامل</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Tajawal', sans-serif; } </style>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen flex items-center justify-center py-10 px-4">

    <div class="w-full max-w-xl bg-white p-8 rounded-xl shadow-[0_0_40px_rgba(0,0,0,0.05)]">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-gray-900 mb-2">إنشاء حساب جديد</h2>
            <p class="text-gray-500 text-sm">أدخل بياناتك للانضمام إلى النظام</p>
        </div>

        @if ($errors->any())
            <div class="mb-5 bg-red-50 border-r-4 border-red-500 p-4 rounded text-sm text-red-700 font-medium">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.auth.register') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">الاسم الكامل *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#6571ff] focus:border-[#6571ff] bg-gray-50">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">الرقم الوطني / الوظيفي *</label>
                    <input type="text" name="national_number" value="{{ old('national_number') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#6571ff] focus:border-[#6571ff] bg-gray-50">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">رقم الهاتف *</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#6571ff] focus:border-[#6571ff] bg-gray-50" placeholder="07XXXXXXXX">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">البريد الإلكتروني (اختياري)</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#6571ff] focus:border-[#6571ff] bg-gray-50" placeholder="example@domain.com">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1.5">نوع الحساب *</label>
                <select name="role_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#6571ff] focus:border-[#6571ff] bg-gray-50">
                    <option value="" disabled selected>اختر الصلاحية</option>
                    <option value="2" {{ old('role_id') == 2 ? 'selected' : '' }}>مستشفى</option>
                    <option value="3" {{ old('role_id') == 3 ? 'selected' : '' }}>موظف</option>
                </select>
                <p class="text-xs text-gray-400 mt-1">مدير النظام لا يمكن إنشاؤه من هذه الواجهة.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">كلمة المرور *</label>
                    <input type="password" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#6571ff] focus:border-[#6571ff] bg-gray-50">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1.5">تأكيد كلمة المرور *</label>
                    <input type="password" name="password_confirmation" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#6571ff] focus:border-[#6571ff] bg-gray-50">
                </div>
            </div>

            <button type="submit" class="w-full py-3 mt-4 rounded-lg text-lg font-bold text-white bg-[#6571ff] hover:bg-opacity-90 focus:outline-none transition shadow-lg">
                إنشاء الحساب
            </button>

            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="text-sm font-medium text-[#6571ff] hover:underline">لديك حساب بالفعل؟ تسجيل الدخول</a>
            </div>
        </form>
    </div>
</body>
</html>
