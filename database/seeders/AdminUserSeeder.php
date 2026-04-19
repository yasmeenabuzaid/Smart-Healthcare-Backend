<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\EmployeeDetail;
use App\Models\Hospital; 
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['role_name' => 'Admin']);

        $hospital = Hospital::firstOrCreate([
            'name' => 'test hospital',
            'phone' => '060000000',  
            'address' => '123 Test Street, Test City'
        ]);

        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'phone_number' => '0790000000',
            'national_number' => '9900112233',
        ]);

        EmployeeDetail::create([
            'user_id' => $user->id,
            'role_id' => $adminRole->id,
            'hospital_id' => $hospital->id, 
        ]);

        $this->command->info('Admin user and Hospital created successfully!');
    }
}