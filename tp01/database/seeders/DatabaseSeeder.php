<?php

namespace Database\Seeders;

use App\Models\Rental;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([SportSeeder::class, CategorySeeder::class, EquipmentSeeder::class, EquipmentSportSeeder::class]); //[,,,,]
        //$this->call(CategorySeeder::class);
        //$this->call(EquipmentSeeder::class);
        //$this->call(EquipmentSportSeeder::class);
        // Je les laisse dans le cas où j'en ai besoin dans le futur
        User::factory(4)->has(Rental::factory(4))->has(Review::factory(4))->create();

    }
}
