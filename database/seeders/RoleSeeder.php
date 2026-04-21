<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insertOrIgnore([
            ['name_ar' => 'مدير النظام', 'name_en' => 'System Admin', 'slug' => 'system_admin'],
            ['name_ar' => 'مدير المستشفى', 'name_en' => 'Hospital Manager', 'slug' => 'hospital_manager'],
            ['name_ar' => 'موظف', 'name_en' => 'Employee', 'slug' => 'employee'],
            ['name_ar' => 'مستخدم عادي', 'name_en' => 'User', 'slug' => 'user'],
        ]);       
    }
}
