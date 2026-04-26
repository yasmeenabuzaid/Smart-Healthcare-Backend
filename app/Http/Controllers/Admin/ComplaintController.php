<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index()
    {
        // بيانات وهمية (Mock Data) لتجربة التصميم قبل ربطها بقاعدة البيانات
        $complaints = [
            (object)[
                'id' => 1,
                'sender_name' => 'أحمد محمود',
                'type' => 'شكوى',
                'subject' => 'تأخير في معالجة طلب المستشفى',
                'date' => '2026-04-20',
                'status' => 'pending',
            ],
            (object)[
                'id' => 2,
                'sender_name' => 'سارة علي',
                'type' => 'اقتراح',
                'subject' => 'إضافة ميزة الإشعارات الفورية',
                'date' => '2026-04-21',
                'status' => 'resolved',
            ]
        ];

        return view('admin.complaints.index', compact('complaints'));
    }
}
