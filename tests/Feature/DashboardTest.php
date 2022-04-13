<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Discount;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestUtils;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_is_displayed()
    {
        $client = TestUtils::setupClient();
        $orders = Order::factory()->count(10)->create(['status' => 1, 'user_id' => $client->id]);

        $response = $this->actingAs(TestUtils::setupAdmin())->get('/dashboard');

        $response->assertStatus(200);
        $data = $response->getOriginalContent()->getData();
        $this->assertCount(5, $data['submittedOrders']);
        $this->assertEquals($data['submittedOrders'][0]->name, $orders[0]->name);
    }

    public function test_client_dashboard_is_displayed()
    {
        $client = TestUtils::setupClient();
        Category::factory()->create();
        $submittedOrders = Order::factory()->count(10)->create(['status' => 1, 'user_id' => $client->id]);
        $unsubmittedOrders = Order::factory()->count(10)->create(['status' => 0, 'user_id' => $client->id]);
        $discount = Discount::factory()->create(['user_id' => $client->id, 'category_id' => 1]);

        $response = $this->actingAs($client)->get('/dashboard');

        $response->assertStatus(200);
        $data = $response->getOriginalContent()->getData();
        $this->assertCount(3, $data['submittedOrders']);
        $this->assertEquals($data['submittedOrders'][0]->name, $submittedOrders[0]->name);
        $this->assertCount(3, $data['unsubmittedOrders']);
        $this->assertEquals($data['unsubmittedOrders'][0]->name, $unsubmittedOrders[0]->name);
        $this->assertCount(1, $data['discounts']);
        $this->assertEquals($data['discounts'][0]->discount, $discount->discount);
    }
}
