<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cities')->insertOrIgnore([
            ['name_ar' => 'عمان', 'name_en' => 'Amman'],
            ['name_ar' => 'إربد', 'name_en' => 'Irbid'],
            ['name_ar' => 'الزرقاء', 'name_en' => 'Zarqa'],
            ['name_ar' => 'البلقاء', 'name_en' => 'Balqa'],
            ['name_ar' => 'الكرك', 'name_en' => 'Karak'],
            ['name_ar' => 'معان', 'name_en' => 'Ma\'an'],
            ['name_ar' => 'الطفيلة', 'name_en' => 'Tafilah'],
            ['name_ar' => 'مادبا', 'name_en' => 'Madaba'],
            ['name_ar' => 'جرش', 'name_en' => 'Jerash'],
            ['name_ar' => 'عجلون', 'name_en' => 'Ajloun'],
            ['name_ar' => 'المفرق', 'name_en' => 'Mafraq'],
            ['name_ar' => 'العقبة', 'name_en' => 'Aqaba'],
        ]);   
    }
}
