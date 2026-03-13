<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    public function test_review_destroy(): void 
    {

        //appelle notre DatabaseSeeder (on y seed avec des factories ou des seeds .sql)
        $this->seed();

        $response = $this->delete('/api/review/1');

        $response->assertStatus(200);

        $this->assertDatabaseMissing('reviews', ['id' => '1']);
    }

    public function test_review_destroy_invalid_content(): void
    {
        $this->seed();

        $response = $this->delete('/api/review/100');

        $response->assertStatus(404);
    }
}
