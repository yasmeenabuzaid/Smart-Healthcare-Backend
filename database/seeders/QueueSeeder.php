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

                    $exists = DB::table('queues')
                        ->where('user_id', $appointment->user_id)
                        ->where('department_id', $department->id)
                        ->where('date', $appointment->date)
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    $lastQueueNumber = DB::table('queues')
                        ->where('department_id', $department->id)
                        ->where('date', $appointment->date)
                        ->max('queue_number');
                    
                    $queueNumber = $lastQueueNumber ? $lastQueueNumber + 1 : 1;

                    $keyUnique = $appointment->user_id.'-'.$department->id.'-'.$appointment->date;

                    DB::table('queues')->insert([
                        'appointment_id' => $appointment->id,
                        'user_id' => $appointment->user_id,
                        'department_id' => $department->id,

                        'queue_number' => $queueNumber,

                        'expected_time' => Carbon::parse($appointment->date)
                            ->setTime(8, 0)
                            ->addMinutes(($queueNumber - 1) * 10),
                        'date' => $appointment->date,
                        'is_arrived' => false,

                        'arrived_at' => null,

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
                $date = Carbon::today()->toDateString();

                for ($i = 1; $i <= $queueCount; $i++) {
                    $lastQueueNumber = DB::table('queues')
                        ->where('department_id', $department->id)
                        ->where('date', $date)
                        ->max('queue_number');

                    $queueNumber = $lastQueueNumber ? $lastQueueNumber + 1 : 1;

                    DB::table('queues')->insert([
                        'appointment_id' => null,
                        'user_id' => $users->random(),
                        'department_id' => $department->id,

                        'queue_number' => $queueNumber,

                        'expected_time' => Carbon::today()
                            ->setTime(9, 0)
                            ->addMinutes(($i - 1) * 7),
                        'date' => $date,
                        'is_arrived' => false,

                        'arrived_at' => null,

                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }    
    }
}
