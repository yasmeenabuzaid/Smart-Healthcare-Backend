<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Hospital;
use App\Models\Employee;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIds = User::where('role_id', 3)->pluck('id')->toArray();

        $hospitalIds = Hospital::pluck('id')->toArray();

        $employeesCount = count($userIds);
        $hospitalsCount = count($hospitalIds);

        if ($employeesCount == 0 || $hospitalsCount == 0) {
            return;
        }

        $employeesPerHospital = intdiv($employeesCount, $hospitalsCount);

        $remainingEmployees = $employeesCount % $hospitalsCount;

        $userIndex = 0;

        foreach ($hospitalIds as $hospitalIndex => $hospitalId) {

            $currentHospitalEmployees = $employeesPerHospital;

            if ($remainingEmployees > 0) {
                $currentHospitalEmployees++;
                $remainingEmployees--;
            }

            for ($i = 0; $i < $currentHospitalEmployees; $i++) {

                if (!isset($userIds[$userIndex])) {
                    break 2;
                }

                Employee::create([
                    'user_id' => $userIds[$userIndex],
                    'hospital_id' => $hospitalId,

                    'job_title_ar' => fake()->randomElement([
                        'موظف استقبال',
                        'ممرض',
                        'فني مختبر',
                        'مساعد طبيب',
                        'محاسب',
                    ]),

                    'job_title_en' => fake()->randomElement([
                        'Receptionist',
                        'Nurse',
                        'Lab Technician',
                        'Doctor Assistant',
                        'Cashier',
                    ]),
                ]);

                $userIndex++;
            }
        }    
    }
}
