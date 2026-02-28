<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Equipment;
use App\Models\Rental;
use App\Models\Review;
use App\Models\User;
use Database\Factories\RentalFactory;
use Database\Factories\UserFactory;
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
        $this->call(SportSeeder::class); //[,,,,]
        $this->call(CategorySeeder::class);
        $this->call(EquipmentSeeder::class);
        $this->call(EquipmentSportSeeder::class);

        //Equipment::factory(4)->has(Category::factory(4))->create();
        //User::factory(4)->create();
        //Review::factory(4)->has(User::factory(4), Rental::factory(4));
        Review::factory(4)->for(User::factory(4))->create(); //ptete realtion a revoir
        //User::factory()->create([
        //    'name' => 'Test User',
        //    'email' => 'test@example.com',
        //]);
        //Equipment::all()->each(function ($equipment) use ($sport) {
        //    $equipment->sport_id()->attach(
        //        $sport->randomElement(0, $sport->count())->pluck('id')->toArray()
        //    );
        //});
    }
}
