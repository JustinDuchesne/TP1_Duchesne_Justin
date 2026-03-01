<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Equipment>
 */
class EquipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        //faker use foreign key laravel requête google Ai (google search?)
        return [
            'name' => fake()->text(10),
            'description' => fake()->text(255),
            'daily_price' => fake()->numberBetween(0,10000),
            'category_id' => \App\Models\Category::all()->random()->id
        ];
    }
}
