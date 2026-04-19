<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>بوابة الكوادر الطبية والإدارية - النظام الشامل</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Tajawal', sans-serif; }
        .bg-medical-pattern {
            background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.05"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-900 h-screen flex overflow-hidden">

    <div class="hidden lg:flex lg:w-1/2 bg-blue-900 text-white relative overflow-hidden bg-medical-pattern">
        <div class="absolute inset-0 bg-gradient-to-b from-blue-900/90 to-blue-800/90"></div>
        
        <div class="relative z-10 flex flex-col justify-between p-12 w-full">
            <div>
                <div class="flex items-center gap-3 mb-12">
                    <div class="w-12 h-12 bg-white text-blue-900 rounded-lg flex items-center justify-center font-black text-2xl shadow-lg">H</div>
                    <span class="text-2xl font-bold tracking-wide">النظام الرقمي الشامل</span>
                </div>
                
                <h1 class="text-4xl font-extrabold mb-6 leading-tight">بوابة العمليات <br> والإدارة الطبية</h1>
                <p class="text-blue-100 text-lg mb-8 max-w-md">
                    نظام متكامل صُمم لتقليل الضغط الإداري، أتمتة توجيه المرضى، وتوفير بيئة عمل منظمة للكوادر الطبية والإدارية.
                </p>

                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-blue-800 flex items-center justify-center shrink-0">🩺</div>
                        <div>
                            <h3 class="font-bold text-lg">التوجيه المتسلسل الآلي</h3>
                            <p class="text-blue-200 text-sm">واجهة مبسطة بلمسة زر واحدة (إنهاء / تحويل) لتقليل الجهد التقني على الطبيب.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-blue-800 flex items-center justify-center shrink-0">⚡</div>
                        <div>
                            <h3 class="font-bold text-lg">التعريف السريع وإدارة الطوارئ</h3>
                            <p class="text-blue-200 text-sm">استخراج الملفات عبر الرقم الوطني بثوانٍ، مع واجهات تصنيف (Triage) لإدراج الحالات المستعجلة فوراً.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-blue-800 flex items-center justify-center shrink-0">📊</div>
                        <div>
                            <h3 class="font-bold text-lg">تحليلات الأداء التشغيلي</h3>
                            <p class="text-blue-200 text-sm">مراقبة أوقات الذروة ومتوسط الانتظار لدعم اتخاذ قرارات توزيع الموارد.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-sm text-blue-300">
&copy; {{ date('Y') }} تطوير مشروع مسابقة ولي العهد - نسخة الكوادر الطبية (MVP).
            </div>
        </div>
    </div>

    <div class="w-full lg:w-1/2 flex items-center justify-center bg-white p-8 sm:p-12">
        <div class="w-full max-w-md">
            <div class="flex lg:hidden items-center gap-3 mb-8 justify-center">
                <div class="w-10 h-10 bg-blue-600 text-white rounded-lg flex items-center justify-center font-bold text-xl">H</div>
                <span class="text-xl font-bold text-gray-900">النظام الشامل</span>
            </div>

            <div class="text-center mb-8">
                <h2 class="text-3xl font-extrabold text-gray-900 mb-2">تسجيل الدخول</h2>
                <p class="text-gray-500 text-sm">يرجى إدخال بيانات الاعتماد الخاصة بك للوصول إلى النظام</p>
            </div>

            @if ($errors->any())
                <div class="mb-4 bg-red-50 border-r-4 border-red-500 p-4 rounded">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 text-red-500">⚠</div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 font-medium">البيانات المدخلة غير متطابقة مع سجلاتنا.</p>
                        </div>
                    </div>
                </div>
            @endif

<form method="POST" action="{{ route('admin.auth.login') }}" class="space-y-6">
                    @csrf



                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">الرقم الوظيفي أو البريد الإلكتروني</label>
                    <input id="email" type="text" name="email" required autofocus class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition" placeholder="مثال: DOC-12345">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">كلمة المرور</label>
                    <input id="password" type="password" name="password" required class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 transition" placeholder="••••••••">
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded cursor-pointer">
                        <label for="remember_me" class="ml-2 mr-2 block text-sm text-gray-900 cursor-pointer">تذكرني على هذا الجهاز</label>
                    </div>

                    <div class="text-sm">
<a href="#" class="font-medium text-blue-600 hover:text-blue-500 transition">                            هل نسيت كلمة المرور؟
                        </a>
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-lg font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                        تسجيل الدخول للنظام
                    </button>
                </div>
            </form>
            
            <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                <p class="text-sm text-gray-500">لطلب الدعم الفني، يرجى التواصل مع قسم الـ IT الداخلي على التحويلة 101.</p>
            </div>
        </div>
    </div>

</body>
</html>