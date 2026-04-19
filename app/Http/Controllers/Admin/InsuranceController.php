<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InsuranceRequest;
use Illuminate\Http\Request;

class InsuranceController extends Controller
{
    public function index()
    {
        return view('admin.insurance.index');
    }

    public function fetch(Request $request)
    {
        $query = InsuranceRequest::query();

        // فلترة بالبحث (اسم المريض أو رقم الهاتف أو شركة التأمين)
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('patient_name', 'like', "%{$request->search}%")
                  ->orWhere('patient_phone', 'like', "%{$request->search}%")
                  ->orWhere('insurance_company', 'like', "%{$request->search}%");
            });
        }

        // فلترة بالحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->latest()->paginate(10));
    }

    public function show(InsuranceRequest $insuranceRequest)
    {
        // سيرجع البيانات ومعها حقل image_url الذي أنشأناه في الموديل
        return response()->json($insuranceRequest);
    }

    // دالة واحدة للقبول والرفض مع إمكانية إضافة ملاحظة
    public function updateStatus(Request $request, InsuranceRequest $insuranceRequest)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_notes' => 'nullable|string'
        ]);

        $insuranceRequest->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes
        ]);

        return response()->json(['message' => 'تم تحديث حالة الطلب بنجاح']);
    }
}
