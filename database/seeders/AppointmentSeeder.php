<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = DB::table('users')->pluck('id');
        
        $departments = DB::table('departments')
            ->where('requires_appointment', true)
            ->pluck('id');

        foreach ($users as $userId) {

            $departmentId = $departments->random();

            DB::table('appointments')->insert([
                'user_id' => $userId,
                'department_id' => $departmentId,
                'date' => Carbon::now()->addDays(rand(1, 20))->format('Y-m-d'),
                'time' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }    
    }
}
