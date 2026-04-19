<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use Illuminate\Http\Request;

class HospitalController extends Controller
{
    // عرض واجهة إدارة المستشفيات
    public function index()
    {
        return view('admin.hospitals.index');
    }

    // جلب البيانات مع الفلترة والبحث (AJAX)
    public function fetch(Request $request)
    {
        $query = Hospital::query();

        // 1. البحث النصي (اسم، إيميل، أو هاتف)
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        // 2. الفلترة حسب النوع (خاص، حكومي، تخصصي)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // 3. الفلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // إرجاع البيانات مرتبة من الأحدث للأقدم
        $hospitals = $query->latest()->paginate(10);

        return response()->json($hospitals);
    }

    // عرض تفاصيل مستشفى محدد
    public function show(Hospital $hospital)
    {
        // جلب المستشفى مع عدد الموظفين التابعين له (إن وجد العلاقة)
        // $hospital->loadCount('employees');

        return response()->json($hospital);
    }

    // إيقاف أو حذف المستشفى (Soft Delete)
    public function destroy(Hospital $hospital)
    {
        $hospital->delete();
        return response()->json(['message' => 'تم حذف المستشفى بنجاح']);
    }
}
