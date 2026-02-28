<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rental>
 */
class RentalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'start_date' => fake()->date('Y-m-d', 'now'), //fait pas trop de sense mais ca va marcher temporairement
            'end_date' => fake()->date('Y-m-d', 'now'),
            'total_price' => fake()->numberBetween(0,10000),
            'user_id' => fake()->randomElement('\App\Models\User'::class('id')),
            'equipment_id' => fake()->randomElement('\App\Models\Equipment'::class('id'))
        ];
    }
}
