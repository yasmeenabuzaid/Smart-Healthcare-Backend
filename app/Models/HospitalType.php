<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Hospital;

class HospitalType extends Model
{
    use HasFactory;
    protected $table = 'hospital_types';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name_ar',
        'name_en',
        'slug',
    ];

    public function hospitals()
    {
        return $this->hasMany(Hospital::class);
    }
}
