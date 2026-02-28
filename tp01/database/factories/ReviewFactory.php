<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rating' => fake()->numberBetween(1, 10),
            'comment' => fake()->text(255),
            'user_id' => fake()->randomElement('\App\Models\User'::class('id')),
            'rental_id' => fake()->randomElement('\App\Models\Rental'::class('id'))
        ];
    }
}
