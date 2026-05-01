<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hospital;
use App\Models\City; // تأكد من وجود مودل City
use App\Models\HospitalType;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 1. حالة مدير النظام (Admin)
        if ($user->role_id == 1) {
            // قم بنقل الكود الذي أرسلته لي إلى هذا المسار: resources/views/dashboard/admin.blade.php
            return view('dashboard.admin');
        }

        // 2. حالة المستشفى (Hospital)
        if ($user->role_id == 2) {
            $hospital = Hospital::where('user_id', $user->id)->first();

            // أ. لم يقم بتسجيل بيانات المستشفى بعد
            if (!$hospital) {
                $cities = City::all();
                $types = HospitalType::all();
                return view('dashboard.hospital_setup', compact('cities', 'types'));
            }

            // ب. الحساب قيد المراجعة
            if ($hospital->status == 'pending') {
                return view('dashboard.hospital_pending');
            }

            // ج. الحساب مرفوض
            if ($hospital->status == 'rejected') {
                return view('dashboard.hospital_rejected', compact('hospital'));
            }

            // د. الحساب معتمد (لوحة تحكم المستشفى الفعلية)
            return view('dashboard.hospital', compact('hospital'));
        }

        // 3. حالة الموظف (Employee) - سنبنيها لاحقاً
        if ($user->role_id == 3) {
            return view('dashboard.employee');
        }

        abort(403, 'Unauthorized action.');
    }
}
