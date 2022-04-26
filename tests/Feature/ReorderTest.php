<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestUtils;

class ReorderTest extends TestCase
{
    use RefreshDatabase;

    public function test_reorder_is_shown()
    {
        Category::factory()->count(5)->create();

        $response = $this->actingAs(TestUtils::setupAdmin())->get('/reorder/categories');

        $response->assertStatus(200);
        $data = $response->getOriginalContent()->getData();
        $this->assertCount(5, $data['items']);
    }

    public function test_items_are_reordered()
    {
        Category::factory()->count(5)->create();
        $payload = [
            '1' => '2',
            '2' => '3',
            '3' => '4',
            '4' => '5',
            '5' => '1',
        ];

        $response = $this->actingAs(TestUtils::setupAdmin())->post('/reorder/categories', $payload);

        $response->assertStatus(302);
        $this->assertDatabaseHas('categories', ['id' => 1, 'position' => 2]);
        $this->assertDatabaseHas('categories', ['id' => 5, 'position' => 1]);
    }
}
