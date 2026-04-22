<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QueueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = DB::table('users')->pluck('id');

        $departments = DB::table('departments')->get();

        $queueCounters = [];

        foreach ($departments as $department) {

            /*
            =====================================================
            1) Departments WITH appointments
            =====================================================
            */
            if ($department->requires_appointment) {

                $appointments = DB::table('appointments')
                    ->where('department_id', $department->id)
                    ->get();

                foreach ($appointments as $appointment) {

                    $key = $department->id . '_' . $appointment->date;

                    if (!isset($queueCounters[$key])) {
                        $queueCounters[$key] = 1;
                    } else {
                        $queueCounters[$key]++;
                    }

                    $queueNumber = $queueCounters[$key];

                    DB::table('queues')->insert([
                        'appointment_id' => $appointment->id,
                        'user_id' => $appointment->user_id,
                        'department_id' => $department->id,

                        'queue_number' => $queueNumber,

                        'expected_time' => Carbon::parse($appointment->date)
                            ->setTime(8, 0)
                            ->addMinutes(($queueNumber - 1) * 10),

                        'is_present' => false,
                        'is_called' => false,
                        'is_served' => false,

                        'called_at' => null,
                        'served_at' => null,

                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            /*
            =====================================================
            2) Departments WITHOUT appointments (walk-in)
            =====================================================
            */
            else {

                $queueCount = rand(5, 15);

                for ($i = 1; $i <= $queueCount; $i++) {

                    DB::table('queues')->insert([
                        'appointment_id' => null,
                        'user_id' => $users->random(),
                        'department_id' => $department->id,

                        'queue_number' => $i,

                        'expected_time' => Carbon::today()
                            ->setTime(9, 0)
                            ->addMinutes(($i - 1) * 7),

                        'is_present' => false,
                        'is_called' => false,
                        'is_served' => false,

                        'called_at' => null,
                        'served_at' => null,

                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }    
    }
}
