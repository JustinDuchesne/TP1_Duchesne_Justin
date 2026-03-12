<?php

namespace Tests\Feature;

use App\Models\Equipment;
use App\Models\Rental;
use App\Models\Review;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\EquipmentSeeder;
use Database\Seeders\EquipmentSportSeeder;
use Database\Seeders\SportSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EquipmentTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    public function test_equipment_index(): void
    {
        $this->seed();

        $response = $this->get('/api/equipment');

        $response->assertJsonCount(20, "data");
        $response->assertStatus(200);
    }

    public function test_equipment_show(): void
    {
        $this->seed();

        $response = $this->get('/api/equipment/1');

        $response->assertJsonFragment([
            'name' => 'Vélo de montagne',                 
            'description'=> 'Vélo tout-terrain',
            'daily_price' => 35.00,
            'category_id' => 4
        ]);
        $response->assertStatus(200);
    }

    public function test_equipment_show_not_found(): void
    {
        $this->seed();

        $response = $this->get('/api/equipment/100');

        $response->assertStatus(404);
    }

    public function test_equipment_popularity(): void
    {
        //$this->seed(); 

        $this->seed([SportSeeder::class, CategorySeeder::class, EquipmentSeeder::class, EquipmentSportSeeder::class]);
        User::factory(4)->create();

        Rental::insert([
            'start_date' => '2015-01-01',
            'end_date' => '2020-11-05',
            'total_price' => 1000,
            'user_id' => 1,
            'equipment_id' => 1
        ]);

        Rental::insert([
            'start_date' => '2015-01-01',
            'end_date' => '2020-11-05',
            'total_price' => 1000,
            'user_id' => 2,
            'equipment_id' => 1
        ]);

        Review::insert([
            'rating' => 4,
            'comment' => 'ajjasas',
            'user_id' => 1,
            'rental_id' => 1
        ]);

        Review::insert([
            'rating' => 5,
            'comment' => 'ajjasas',
            'user_id' => 2,
            'rental_id' => 1
        ]);


        $response = $this->get('/api/equipment/popularity/1');

        $response->assertJsonFragment([
            'popularity' => 4.8,
        ]);
        $response->assertStatus(200);
    }

    public function test_equipment_popularity_not_found(): void
    {
        $this->seed();

        $response = $this->get('/api/equipment/popularity/100');

        $response->assertStatus(404);
    }

    public function test_equipment_average(): void
    {
        //$this->seed();
        $this->seed([SportSeeder::class, CategorySeeder::class, EquipmentSeeder::class, EquipmentSportSeeder::class]);
        User::factory(4)->create();

        Rental::insert([
            'start_date' => '2000-01-01',
            'end_date' => '2008-01-05',
            'total_price' => 200,
            'user_id' => 1,
            'equipment_id' => 1
        ]);

        Rental::insert([
            'start_date' => '2015-01-01',
            'end_date' => '2020-11-05',
            'total_price' => 1000,
            'user_id' => 1,
            'equipment_id' => 1
        ]);

        $response = $this->get('/api/equipment/average/1?min_date=1800-03-03&max_date=2026-10-10');

        $response->assertJsonFragment([
            'average' => 600,
        ]);
        $response->assertStatus(200);
    }

    public function test_equipment_average_no_dates(): void
    {
        //$this->seed();
        $this->seed([SportSeeder::class, CategorySeeder::class, EquipmentSeeder::class, EquipmentSportSeeder::class]);
        User::factory(4)->create();

        Rental::insert([
            'start_date' => '2000-01-01',
            'end_date' => '2008-01-05',
            'total_price' => 200,
            'user_id' => 1,
            'equipment_id' => 1
        ]);

        Rental::insert([
            'start_date' => '2015-01-01',
            'end_date' => '2020-11-05',
            'total_price' => 1000,
            'user_id' => 1,
            'equipment_id' => 1
        ]);

        $response = $this->get('/api/equipment/average/1');

        $response->assertJsonFragment([
            'average' => 600,
        ]);
        $response->assertStatus(200);
    }

    public function test_equipment_average_no_max_dates(): void
    {
        //$this->seed();
        $this->seed([SportSeeder::class, CategorySeeder::class, EquipmentSeeder::class, EquipmentSportSeeder::class]);
        User::factory(4)->create();

        Rental::insert([
            'start_date' => '2000-01-01',
            'end_date' => '2008-01-05',
            'total_price' => 200,
            'user_id' => 1,
            'equipment_id' => 1
        ]);

        Rental::insert([
            'start_date' => '2015-01-01',
            'end_date' => '2020-11-05',
            'total_price' => 1000,
            'user_id' => 1,
            'equipment_id' => 1
        ]);

        $response = $this->get('/api/equipment/average/1?min_date=2000-03-03');

        $response->assertJsonFragment([
            'average' => 1000,
        ]);
        $response->assertStatus(200);
    }
    public function test_equipment_average_no_min_dates(): void
    {
        //$this->seed();
        $this->seed([SportSeeder::class, CategorySeeder::class, EquipmentSeeder::class, EquipmentSportSeeder::class]);
        User::factory(4)->create();

        Rental::insert([
            'start_date' => '2000-01-01',
            'end_date' => '2008-01-05',
            'total_price' => 200,
            'user_id' => 1,
            'equipment_id' => 1
        ]);

        Rental::insert([
            'start_date' => '2015-01-01',
            'end_date' => '2020-11-05',
            'total_price' => 1000,
            'user_id' => 1,
            'equipment_id' => 1
        ]);

        $response = $this->get('/api/equipment/average/1?max_date=2009-03-22');

        $response->assertJsonFragment([
            'average' => 200,
        ]);
        $response->assertStatus(200);
    }

    public function test_equipment_average_invalid_dates(): void
    {
        $this->seed();

        $response = $this->get('/api/equipment/average/3?min_date=2026-10-10&max_date=1800-03-03');

        $response->assertJsonFragment([
            'error' => 'La date minimum ne peut pas être suppérieur à la date maximum', //pas sur que c'est juste réponse..
        ]);
        $response->assertStatus(422);
    }

    public function test_equipment_average_not_found(): void
    {
        $this->seed();

        $response = $this->get('/api/equipment/average/100');

        $response->assertStatus(404);
    }
}
