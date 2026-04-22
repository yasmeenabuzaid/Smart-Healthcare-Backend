<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DepartmentScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departmentIds = DB::table('departments')->pluck('id');

        $days = ['sat','sun','mon','tue','wed','thu','fri'];

        $schedules = [
            [
                'service_type_ar' => 'عيادة',
                'service_type_en' => 'Clinic',
                'start_time' => '08:00:00',
                'end_time' => '15:00:00',
                'avg_visit_duration' => 10,
                'max_patients' => 40,
                'is_closed' => false,
            ],
            [
                'service_type_ar' => 'عمليات',
                'service_type_en' => 'Operations',
                'start_time' => '00:00:00',
                'end_time' => '23:59:59',
                'avg_visit_duration' => 5,
                'max_patients' => 999,
                'is_closed' => false,
            ],
            [
                'service_type_ar' => 'فحص مخبري',
                'service_type_en' => 'Lab',
                'start_time' => '08:00:00',
                'end_time' => '14:00:00',
                'avg_visit_duration' => 15,
                'max_patients' => 60,
                'is_closed' => false,
            ],
            [
                'service_type_ar' => 'عطلة',
                'service_type_en' => 'Holiday',
                'start_time' => '00:00:00',
                'end_time' => '00:00:00',
                'avg_visit_duration' => 0,
                'max_patients' => 0,
                'is_closed' => true,
            ],
        ];

        foreach ($departmentIds as $departmentId) {
            foreach ($days as $day) {

                $schedule = $schedules[array_rand($schedules)];

                DB::table('department_schedules')->insert([
                    'department_id' => $departmentId,
                    'day_of_week' => $day,

                    'start_time' => $schedule['start_time'],
                    'end_time' => $schedule['end_time'],

                    'service_type_ar' => $schedule['service_type_ar'],
                    'service_type_en' => $schedule['service_type_en'],

                    'avg_visit_duration' => $schedule['avg_visit_duration'],
                    'max_patients' => $schedule['max_patients'],

                    'is_closed' => $schedule['is_closed'],

                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }    
    }
}