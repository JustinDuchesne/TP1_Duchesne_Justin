<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_store_user(): void
    {
        $this->seed();
        $json = ['first_name'=>'test song', 'last_name'=>'asasa', 'email'=>'truc@gmail.com', 'phone' => '418-418-4188'];

        $response = $this->postJson('/api/user', $json);

        $response->assertJsonFragment($json);
        $response->assertStatus(201);
        $this->assertDatabaseHas('users', $json);
    }

    public function test_store_user_invalid_data(): void
    {
        $this->seed();
        $json = ['first_name'=>'', 'last_name'=>'', 'email'=>'invalid', 'phone' => 'invalid'];

        $response = $this->postJson('/api/user', $json);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('users', $json);
    }

    public function test_store_user_missing_data(): void
    {
        $this->seed();
        $json = ['last_name'=>'sdsdsd', 'email'=>'truc@gmail.com', 'phone' => '418-418-4188'];

        $response = $this->postJson('/api/user', $json);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('users', $json);
    }

    public function test_update_user(): void
    {
        $this->seed();
        $json = ['first_name' => 'allo','last_name'=>'asasa', 'email'=>'truc@gmail.com', 'phone' => '418-418-4188'];

        $response = $this->patchJson('/api/user/1', $json);

        $response->assertJsonFragment([
            'first_name' => 'allo',
            'last_name' => 'asasa',                 
            'email' => 'truc@gmail.com',
            'phone' => '418-418-4188',
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', $json);
    }

    public function test_update_user_invalid_id(): void
    {
        $this->seed();
        $json = ['last_name'=>'asasa', 'email'=>'truc@gmail.com', 'phone' => '418-418-4188'];

        $response = $this->patchJson('/api/user/100', $json);

        $response->assertStatus(422);
    }

    public function test_update_user_invalid_data(): void
    {
        $this->seed();
        $json = ['last_name'=>'', 'email'=>'invalid', 'phone' => 'invalid'];

        $response = $this->patchJson('/api/user/1', $json);

        $response->assertStatus(422);
    }
}
