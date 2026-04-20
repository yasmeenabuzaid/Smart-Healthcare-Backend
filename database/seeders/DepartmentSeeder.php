<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hospitalIds = DB::table('hospitals')->pluck('id');

        $departments = [
            ['name_ar' => 'الطوارئ', 'name_en' => 'Emergency', 'requires_appointment' => false],
            ['name_ar' => 'العيادة العامة', 'name_en' => 'General Clinic', 'requires_appointment' => false],
            ['name_ar' => 'عيادة الباطنية', 'name_en' => 'Internal Medicine', 'requires_appointment' => true],
            ['name_ar' => 'عيادة الأطفال', 'name_en' => 'Pediatrics', 'requires_appointment' => false],
            ['name_ar' => 'عيادة النسائية والتوليد', 'name_en' => 'Gynecology & Obstetrics', 'requires_appointment' => false],
            ['name_ar' => 'عيادة العيون', 'name_en' => 'Ophthalmology', 'requires_appointment' => true],
            ['name_ar' => 'عيادة الأنف والأذن والحنجرة', 'name_en' => 'ENT', 'requires_appointment' => true],
            ['name_ar' => 'عيادة الأسنان', 'name_en' => 'Dentistry', 'requires_appointment' => false],
            ['name_ar' => 'عيادة القلب', 'name_en' => 'Cardiology', 'requires_appointment' => true],
            ['name_ar' => 'عيادة الأعصاب', 'name_en' => 'Neurology', 'requires_appointment' => true],
            ['name_ar' => 'عيادة الجلدية', 'name_en' => 'Dermatology', 'requires_appointment' => true],
            ['name_ar' => 'عيادة العظام', 'name_en' => 'Orthopedics', 'requires_appointment' => true],
            ['name_ar' => 'الأشعة', 'name_en' => 'Radiology', 'requires_appointment' => false],
            ['name_ar' => 'تصوير الرنين المغناطيسي', 'name_en' => 'MRI', 'requires_appointment' => true],
            ['name_ar' => 'التصوير الطبقي (CT)', 'name_en' => 'CT Scan', 'requires_appointment' => true],
            ['name_ar' => 'المختبر', 'name_en' => 'Laboratory', 'requires_appointment' => false],
            ['name_ar' => 'الصيدلية', 'name_en' => 'Pharmacy', 'requires_appointment' => false],
            ['name_ar' => 'العناية الحثيثة', 'name_en' => 'ICU', 'requires_appointment' => false],
            ['name_ar' => 'العناية المركزة لحديثي الولادة', 'name_en' => 'NICU', 'requires_appointment' => false],
            ['name_ar' => 'غسيل الكلى', 'name_en' => 'Dialysis', 'requires_appointment' => false],
            ['name_ar' => 'العلاج الطبيعي', 'name_en' => 'Physiotherapy', 'requires_appointment' => true],
            ['name_ar' => 'الجراحة العامة', 'name_en' => 'General Surgery', 'requires_appointment' => true],
            ['name_ar' => 'جراحة القلب', 'name_en' => 'Cardiac Surgery', 'requires_appointment' => true],
            ['name_ar' => 'جراحة الأعصاب', 'name_en' => 'Neurosurgery', 'requires_appointment' => true],
            ['name_ar' => 'الأورام', 'name_en' => 'Oncology', 'requires_appointment' => true],
            ['name_ar' => 'التخدير', 'name_en' => 'Anesthesia', 'requires_appointment' => false],
            ['name_ar' => 'التغذية', 'name_en' => 'Nutrition', 'requires_appointment' => true],
            ['name_ar' => 'الطب النفسي', 'name_en' => 'Psychiatry', 'requires_appointment' => true],
        ];

        foreach ($hospitalIds as $hospitalId) {
            foreach ($departments as $department) {
                DB::table('departments')->insertOrIgnore([
                    'hospital_id' => $hospitalId,
                    'name_ar' => $department['name_ar'],
                    'name_en' => $department['name_en'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }   
    }
}
