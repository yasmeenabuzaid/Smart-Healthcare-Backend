<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateHospitalStatusRequest;

class HospitalController extends Controller
{
    public function index()
    {
        return view('admin.hospitals.index');
    }

    public function fetch(Request $request)
    {
        $query = Hospital::query()
            ->with('type:id,name_ar,name_en');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name_ar', 'like', "%{$request->search}%")
                  ->orWhere('name_en', 'like', "%{$request->search}%")
                  ->orWhere('hospital_email', 'like', "%{$request->search}%")
                  ->orWhere('phone', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('hospital_type_id', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $hospitals = $query
            ->select([
                'id',
                'name_ar',
                'name_en',
                'phone',
                'hospital_email',
                'hospital_type_id',
                'status',
                'created_at',
            ])
            ->latest('created_at')
            ->paginate(10);
        return response()->json($hospitals);
    }

    public function show(Hospital $hospital)
    {    
        $hospital->load([
            'city:id,name_ar,name_en',
            'type:id,name_ar,name_en',
            'owner:id,name,email,phone',
        ]);
    
        return response()->json([
            'id' => $hospital->id,
            'name_ar' => $hospital->name_ar,
            'name_en' => $hospital->name_en,
            'description_ar' => $hospital->description_ar,
            'description_en' => $hospital->description_en,
            'logo' => $hospital->logo,
            'cover_image' => $hospital->cover_image,
            'phone' => $hospital->phone,
            'emergency_phone' => $hospital->emergency_phone,
            'hospital_email' => $hospital->hospital_email,
            'website_link' => $hospital->website_link,
            'address_ar' => $hospital->address_ar,
            'address_en' => $hospital->address_en,
            'license_number' => $hospital->license_number,
            'status' => $hospital->status,
            'city' => $hospital->city,
            'type' => $hospital->type,
            'owner' => $hospital->owner,
            'created_at' => $hospital->created_at,
        ]);
    }

    public function updateStatus(UpdateHospitalStatusRequest $request, Hospital $hospital)
    {
        $hospital->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Hospital status updated successfully.',
            'data' => $hospital->fresh()
        ]);
    }


    public function destroy(Hospital $hospital)
    {
        $hospital->delete();
        return response()->json(['message' => 'تم حذف المستشفى بنجاح']);
    }
}
