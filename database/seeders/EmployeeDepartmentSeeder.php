<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Department;
use App\Models\EmployeeDepartment;

class EmployeeDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::all(['id', 'hospital_id']);

        foreach ($employees as $employee) {

            $departmentIds = Department::where('hospital_id', $employee->hospital_id)
                ->pluck('id')
                ->toArray();

            if (empty($departmentIds)) {
                continue;
            }

            $assignCount = rand(1, min(3, count($departmentIds)));

            $randomDepartmentIds = collect($departmentIds)
                ->shuffle()
                ->take($assignCount);

            foreach ($randomDepartmentIds as $departmentId) {
                EmployeeDepartment::create([
                    'employee_id' => $employee->id,
                    'department_id' => $departmentId,
                ]);
            }
        }    
    }
}
