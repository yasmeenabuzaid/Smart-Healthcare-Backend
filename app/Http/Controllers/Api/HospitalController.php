<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\HospitalType;
use App\Models\Hospital;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\HospitalRequest;

class HospitalController extends Controller
{
    public function getHospitalTypes()
    {     
        try {
            $types = Cache::remember('hospital_type_index', now()->addDay(), function () {
                return HospitalType::select(['id', 'name_ar', 'name_en'])->get();
            });

            return response()->json([
                'status' => 'success',
                'message' => 'Hospital types retrieved successfully',
                'data' => $types
            ], 200);

        } catch (\Exception $e) {
            Log::error('getHospitalTypes failed:', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve hospital types, please try again later.',
                'data' => null
            ], 500);
        }
    }
    
    public function getHospitalsByType(HospitalRequest $request)
    {     
        try {
        
            $typeId = $request->type_id;
        
            $cacheKey = "hospitals_" . ($typeId ?? 'all');
        
            $hospitals = Cache::remember($cacheKey, 3600, function () use ($typeId) {
        
                return Hospital::select(['id', 'name_ar', 'name_en', 'city_id'])
                    ->with('city:id,name_ar,name_en')
                    ->where('status', 'approved')
                    ->when($typeId, function ($query) use ($typeId) {
                        $query->where('hospital_type_id', $typeId);
                    })
                    ->orderBy('city_id')
                    ->get()
                    ->groupBy('city.name_en');
            });
        
            return response()->json([
                'status' => 'success',
                'message' => 'Hospitals retrieved successfully',
                'data' => $hospitals
            ], 200);
        
        } catch (\Exception $e) {
        
            Log::error('getHospitalsByType failed', [
                'error' => $e->getMessage(),
            ]);
        
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve hospitals, please try again later.',
                'data' => null
            ], 500);
        }
    }
 
    public function getHospitalDetails($id)
    {
        try {
    
            $cacheKey = "hospital_details_{$id}";
    
            $hospital = Cache::remember($cacheKey, 3600, function () use ($id) {
    
                return Hospital::select([
                        'id', 'name_ar', 'name_en',
                        'description_ar', 'description_en', 'logo',
                        'cover_image', 'phone', 'emergency_phone',
                        'hospital_email', 'website_link', 'address_ar',
                        'address_en', 'latitude', 'longitude',
                        'city_id', 'hospital_type_id',
                    ])
                    ->with([
                        'city:id,name_ar,name_en', 
                        'type:id,name_ar,name_en', 
                        'departments:id,name_ar,name_en,requires_appointment,hospital_id'])
                    ->where('id', $id)
                    ->where('status', 'approved')
                    ->first();
            });
    
            if (!$hospital) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Hospital not found',
                    'data' => null
                ], 404);
            }
    
            return response()->json([
                'status' => 'success',
                'message' => 'Hospital details retrieved successfully',
                'data' => $hospital
            ], 200);
    
        } catch (\Exception $e) {
    
            Log::error('getHospitalDetails failed', [
                'error' => $e->getMessage(),
                'hospital_id' => $id
            ]);
    
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve hospital details, please try again later.',
                'data' => null
            ], 500);
        }
    }

}