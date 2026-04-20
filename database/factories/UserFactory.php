<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),

            'email' => $this->faker->unique()->safeEmail(),

            'phone' => $this->faker->unique()->numerify('07########'),

            'national_number' => $this->faker->unique()->numerify('1##########'),

            'password' => Hash::make('password'),

            'role_id' => Role::inRandomOrder()->first()->id,
        ];
    }

}
