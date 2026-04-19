<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InsuranceRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_name', 'patient_phone', 'insurance_company',
        'insurance_number', 'image_path', 'status', 'admin_notes'
    ];


    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
     
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }
}
