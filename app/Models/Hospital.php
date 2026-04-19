<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // استدعاء ميزة الحذف الآمن

class Hospital extends Model
{
    use HasFactory, SoftDeletes; // تفعيل الميزة

    protected $fillable = [
        'name', 'slug', 'description', 'logo',
        'phone', 'emergency_phone', 'email', 'website',
        'governorate', 'address', 'latitude', 'longitude',
        'license_number', 'type', 'status', 'admin_notes'
    ];
}
