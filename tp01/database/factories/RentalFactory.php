<?php

namespace Database\Factories;

use App\Models\Equipment;
use App\Models\User;
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
        $start_date = fake()->date('Y-m-d', 'now');
        $end_date = fake()->dateTimeBetween($start_date, 'now');
        //https://stackoverflow.com/questions/44102483/in-laravel-how-do-i-retrieve-a-random-user-id-from-the-users-table-for-model-fa
        return [
            'start_date' => $start_date,//fake()->date('Y-m-d', 'now'),
            'end_date' => $end_date,//fake()->date('Y-m-d', 'now'),
            'total_price' => fake()->numberBetween(0,10000),
            'user_id' => User::inRandomOrder()->value('id'),
            'equipment_id' => Equipment::inRandomOrder()->value('id')
        ];
    }
}
