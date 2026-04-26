<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DepartmentScheduleController extends Controller
{
    // 1. عرض وإضافة أوقات الدوام في نفس الصفحة
    public function schedule($id)
    {
        $departmentName = "قسم الأشعة المقطعية"; // بيانات وهمية

        $workingDays = [
            (object)['id'=>1, 'day'=>'الأحد', 'start_time'=>'08:00 AM', 'end_time'=>'04:00 PM', 'capacity'=>50, 'status'=>'active'],
            (object)['id'=>2, 'day'=>'الإثنين', 'start_time'=>'08:00 AM', 'end_time'=>'04:00 PM', 'capacity'=>50, 'status'=>'active'],
            (object)['id'=>3, 'day'=>'الخميس', 'start_time'=>'08:00 AM', 'end_time'=>'02:00 PM', 'capacity'=>30, 'status'=>'active'],
        ];

        return view('admin.departments.schedule', compact('departmentName', 'workingDays', 'id'));
    }

    public function storeSchedule(Request $request, $id)
    {
        // سيتم برمجة الحفظ في قاعدة البيانات هنا
        return back()->with('success', 'تم حفظ إعدادات دوام اليوم بنجاح.');
    }

    // 2. لوحة مراقبة الطابور (Queue Dashboard)
    public function queue($id)
    {
        $departmentName = "قسم الأشعة المقطعية";

        $currentServing = (object)['ticket' => 'A-042', 'patient_name' => 'محمد عبدالله', 'time_started' => '10:15 AM'];

        $waitingList = [
            (object)['ticket' => 'A-043', 'patient_name' => 'سارة أحمد', 'wait_time' => '15 دقيقة'],
            (object)['ticket' => 'A-044', 'patient_name' => 'خالد محمود', 'wait_time' => '22 دقيقة'],
            (object)['ticket' => 'A-045', 'patient_name' => 'ياسمين علي', 'wait_time' => '30 دقيقة'],
        ];

        return view('admin.departments.queue', compact('departmentName', 'currentServing', 'waitingList', 'id'));
    }
}
