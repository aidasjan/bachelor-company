<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Discount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestUtils;

class DiscountsTest extends TestCase
{
    use RefreshDatabase;

    public function test_discounts_are_displayed()
    {
        $client = TestUtils::setupClient();
        Category::factory()->create(['id' => 1]);
        Discount::factory()->count(5)->create(['user_id' => 2, 'category_id' => 1]);

        $response = $this->actingAs($client)->get('/discounts');

        $response->assertStatus(200);
        $data = $response->getOriginalContent()->getData();
        $this->assertCount(5, $data['discounts']);
    }

    public function test_discounts_are_stored()
    {
        $admin = TestUtils::setupAdmin();
        $client = TestUtils::setupClient();
        Category::factory()->count(5)->create();
        $payload = [
            'discount_user' => $client->id,
            '1' => '10',
            '4' => '20'
        ];

        $response = $this->actingAs($admin)->post('/discounts', $payload);

        $response->assertStatus(302);
        $this->assertDatabaseCount('discounts', 2);
        $this->assertDatabaseHas('discounts', ['user_id' => $client->id, 'category_id' => 1, 'discount' => 10]);
        $this->assertDatabaseHas('discounts', ['user_id' => $client->id, 'category_id' => 4, 'discount' => 20]);
    }

    public function test_all_discounts_are_stored()
    {
        $admin = TestUtils::setupAdmin();
        $client = TestUtils::setupClient();
        Category::factory()->count(5)->create();
        $payload = [
            'discount_user' => $client->id,
            'discount' => 10,
        ];

        $response = $this->actingAs($admin)->post('/discounts/all', $payload);

        $response->assertStatus(302);
        $this->assertDatabaseCount('discounts', 5);
        $this->assertDatabaseHas('discounts', ['user_id' => $client->id, 'category_id' => 1, 'discount' => 10]);
    }
}
