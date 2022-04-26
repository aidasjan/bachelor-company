<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BackupsTest extends TestCase
{
    use RefreshDatabase;

    public function test_ok_status_with_valid_token()
    {
        Category::factory()->count(5)->create();
        Product::factory()->count(5)->create();

        $response = $this->get('/backup/scheduled/backuptesttoken');

        $response->assertStatus(200);
    }

    public function test_not_found_status_with_invalid_token()
    {
        Category::factory()->count(5)->create();
        Product::factory()->count(5)->create();

        $response = $this->get('/backup/scheduled/invalidtoken');

        $response->assertStatus(404);
    }
}
