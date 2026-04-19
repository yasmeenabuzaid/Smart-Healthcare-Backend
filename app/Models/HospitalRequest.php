<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HospitalRequest extends Model
{
    use HasFactory, SoftDeletes;

    // تحديد الحقول التي يمكن حفظها في قاعدة البيانات
    protected $fillable = [
        'requester_name',
        'requester_email',
        'requester_phone',
        'hospital_name',
        'hospital_address',
        'license_file',
        'status',
        'rejection_reason',
    ];
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
