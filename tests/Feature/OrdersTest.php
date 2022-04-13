<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestUtils;

class OrdersTest extends TestCase
{
    use RefreshDatabase;

    public function test_orders_by_status_are_displayed_for_client()
    {
        Order::factory()->count(5)->create();

        $response = $this->actingAs(TestUtils::setupClient())->get('/orders/status/1');

        $response->assertStatus(200);
        $data = $response->getOriginalContent()->getData();
        $this->assertCount(5, $data['orders']);
    }

    public function test_orders_are_displayed_for_client()
    {
        Order::factory()->count(5)->create();

        $response = $this->actingAs(TestUtils::setupClient())->get('/orders');

        $response->assertStatus(200);
        $data = $response->getOriginalContent()->getData();
        $this->assertCount(5, $data['orders']);
    }

    public function test_orders_are_displayed_for_admin()
    {
        TestUtils::setupClient();
        Order::factory()->count(5)->create();

        $response = $this->actingAs(TestUtils::setupAdmin())->get('/orders');

        $response->assertStatus(200);
        $data = $response->getOriginalContent()->getData();
        $this->assertCount(5, $data['orders']);
    }

    public function test_new_order_is_created()
    {
        $client = TestUtils::setupClient();
        $response = $this->actingAs($client)->post('/orders');

        $response->assertStatus(302);
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseHas('orders', ['user_id' => $client->id]);
    }

    public function test_order_products_are_stored()
    {
        $client = TestUtils::setupClient();
        TestUtils::createProductsWithCategory(5);
        Order::factory()->create(['id' => 1, 'status' => 0, 'user_id' => $client->id]);
        OrderProduct::factory()->create(['order_id' => 1, 'product_id' => 1]);
        $payload = [
            '1' => '10',
            '4' => '20'
        ];

        $response = $this->actingAs($client)
            ->session(['current_order' => 1])
            ->post('/orders/products', $payload);

        $response->assertStatus(302);
        $this->assertDatabaseCount('order_products', 2);
        $this->assertDatabaseHas('order_products', ['product_id' => 1, 'quantity' => 10, 'order_id' => 1]);
    }

    public function test_order_details_are_displayed()
    {
        $client = TestUtils::setupClient();
        TestUtils::createProductsWithCategory(5);
        $order = Order::factory()->create(['id' => 1, 'status' => 0, 'user_id' => $client->id]);
        OrderProduct::factory()->create(['order_id' => 1, 'product_id' => 1]);
        OrderProduct::factory()->create(['order_id' => 1, 'product_id' => 5]);
        
        $response = $this->actingAs($client)
            ->session(['current_order' => 1])
            ->get('/orders/1');

        $response->assertStatus(200);
        $data = $response->getOriginalContent()->getData();
        $this->assertCount(2, $data['orderProducts']);
        $this->assertEquals($order->id, $data['order']->id);
        $this->assertGreaterThan(0, $data['totalOrderPriceEUR']);
        $this->assertEquals(0, $data['totalOrderPriceUSD']);
    }

    public function test_order_is_submitted()
    {
        $client = TestUtils::setupClient();
        Order::factory()->create(['id' => 1, 'status' => 0, 'user_id' => $client->id]);

        $response = $this->actingAs($client)->put('/orders/1');

        $response->assertStatus(302);
        $this->assertDatabaseHas('orders', ['status' => 1]);
    }

    public function test_order_is_destroyed_by_client()
    {
        $client = TestUtils::setupClient();
        Order::factory()->create(['id' => 1, 'status' => 0, 'user_id' => $client->id]);

        $response = $this->actingAs($client)->delete('/orders/1');

        $response->assertStatus(302);
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_unsubmitted_orders_are_destroyed_by_admin()
    {
        Order::factory()->count(5)->create(['status' => 0]);

        $response = $this->actingAs(TestUtils::setupAdmin())->post('/orders/destroyunsubmitted');

        $response->assertStatus(302);
        $this->assertDatabaseMissing('orders', ['status' => 0]);
    }
}
