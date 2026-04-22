<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CitySeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            HospitalTypeSeeder::class,
            HospitalSeeder::class,
            DepartmentSeeder::class,
            DepartmentScheduleSeeder::class,
            FeedbackSeeder::class,
            AppointmentSeeder::class,
            QueueSeeder::class,
        ]);
    }
}
