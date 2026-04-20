<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HospitalTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('hospital_types')->insertOrIgnore([
            [
                'name_ar' => 'مستشفى حكومي',
                'name_en' => 'Government Hospital',
                'slug' => 'government-hospital',
            ],
            [
                'name_ar' => 'مستشفى عسكري',
                'name_en' => 'Military Hospital',
                'slug' => 'military-hospital',
            ],
            [
                'name_ar' => 'مستشفى جامعي',
                'name_en' => 'University Hospital',
                'slug' => 'university-hospital',
            ],
            [
                'name_ar' => 'مركز صحي',
                'name_en' => 'Health Center',
                'slug' => 'health-center',
            ],
            [
                'name_ar' => 'مستشفى تخصصي',
                'name_en' => 'Specialized Hospital',
                'slug' => 'specialized-hospital',
            ],
        ]);      
    }
}
