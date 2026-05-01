<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hospital;

class HospitalProfileController extends Controller
{
    // دالة حفظ بيانات المستشفى لأول مرة
    public function store(Request $request)
    {
        // 1. التحقق من صحة البيانات
        $validated = $request->validate([
            'name_ar'          => 'required|string|max:255',
            'name_en'          => 'required|string|max:255',
            'phone'            => 'required|string|max:20',
            'hospital_type_id' => 'required|exists:hospital_types,id',
            'city_id'          => 'required|exists:cities,id',
            'address_ar'       => 'required|string',
            'license_number'   => 'nullable|string|max:255',
        ]);

        // 2. حفظ البيانات في جدول المستشفيات
        Hospital::create([
            'user_id'          => auth()->id(), // ربط المستشفى بالحساب الحالي
            'name_ar'          => $validated['name_ar'],
            'name_en'          => $validated['name_en'],
            'phone'            => $validated['phone'],
            'hospital_type_id' => $validated['hospital_type_id'],
            'city_id'          => $validated['city_id'],
            'address_ar'       => $validated['address_ar'],
            'address_en'       => $validated['address_ar'], // وضعنا العنوان العربي مؤقتاً لتجنب أخطاء الداتا بيز
            'license_number'   => $validated['license_number'],
            'status'           => 'pending', // الحالة الافتراضية للطلب
        ]);

        // 3. التوجيه مرة أخرى إلى الداشبورد (والذي سيظهر الآن شاشة "قيد المراجعة")
        return redirect()->route('dashboard')->with('success', 'تم إرسال طلبك بنجاح وهو الآن قيد المراجعة.');
    }
}
