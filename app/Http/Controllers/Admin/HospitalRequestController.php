<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HospitalRequest;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HospitalRequestController extends Controller
{
    public function index()
    {
        return view('admin.approvals.index');
    }

    // جلب الطلبات (الديفولت هو المعلقة Pending)
    public function fetch(Request $request)
    {
        $status = $request->get('status', 'pending');

        $requests = HospitalRequest::where('status', $status)
            ->latest()
            ->paginate(10);

        return response()->json($requests);
    }

    // عرض تفاصيل طلب محدد
    public function show(HospitalRequest $hospitalRequest)
    {
        return response()->json($hospitalRequest);
    }

    // الموافقة على الطلب
    public function approve(HospitalRequest $hospitalRequest)
    {
        if ($hospitalRequest->status !== 'pending') {
            return response()->json(['message' => 'هذا الطلب تمت معالجته مسبقاً'], 400);
        }

        DB::transaction(function () use ($hospitalRequest) {
            // 1. إنشاء المستشفى الفعلي
            Hospital::create([
                'name' => $hospitalRequest->hospital_name,
                'email' => $hospitalRequest->requester_email,
                'phone' => $hospitalRequest->requester_phone,
                'address' => $hospitalRequest->hospital_address,
                'status' => 'approved',
            ]);

            // 2. تحديث حالة الطلب
            $hospitalRequest->update(['status' => 'approved']);
        });

        return response()->json(['message' => 'تمت الموافقة وتم إنشاء المستشفى بنجاح']);
    }

    // رفض الطلب
    public function reject(Request $request, HospitalRequest $hospitalRequest)
    {
        $request->validate(['rejection_reason' => 'required|string']);

        $hospitalRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason
        ]);

        return response()->json(['message' => 'تم رفض الطلب بنجاح']);
    }
}
