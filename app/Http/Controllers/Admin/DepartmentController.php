<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        // بيانات تجريبية (Mock Data) للأقسام والخدمات الحالية
        $departments = [
            (object)[
                'id' => 1,
                'name' => 'قسم الطوارئ',
                'type' => 'قسم طبي',
                'status' => 'active',
                'created_at' => '2026-01-10',
            ],
            (object)[
                'id' => 2,
                'name' => 'المختبرات والتحاليل',
                'type' => 'خدمة مساندة',
                'status' => 'active',
                'created_at' => '2026-02-15',
            ],
            (object)[
                'id' => 3,
                'name' => 'العيادات الخارجية',
                'type' => 'قسم طبي',
                'status' => 'maintenance',
                'created_at' => '2026-03-20',
            ]
        ];

        return view('admin.departments.index', compact('departments'));
    }

    public function store(Request $request)
    {
        // التحقق من البيانات
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
        ]);

        // منطق الحفظ في قاعدة البيانات سيتم هنا لاحقاً

        return back()->with('success', 'تمت إضافة القسم إلى النظام بنجاح.');
    }
}
