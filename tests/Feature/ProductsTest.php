<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Discount;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Parameter;
use App\Models\Product;
use App\Models\ProductParameter;
use App\Models\Usage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestUtils;

class ProductsTest extends TestCase
{
    use RefreshDatabase;

    public function test_new_product_is_saved()
    {
        Category::factory()->create();
        $payload = [
            'code' => 'Code',
            'name' => 'Product',
            'price' => 10,
            'currency' => 'EUR',
            'unit' => 'kg',
            'category_id' => 1
        ];

        $response = $this->actingAs(TestUtils::setupAdmin())->post('/products', $payload);

        $response->assertStatus(302);
        $this->assertDatabaseHas('products', ['name' => 'Product']);
    }

    public function test_product_is_updated()
    {
        Category::factory()->create();
        Product::factory()->create();
        $payload = [
            'code' => 'Code',
            'name' => 'New Product',
            'price' => 10,
            'currency' => 'EUR',
            'unit' => 'kg',
            'category_id' => 1
        ];

        $response = $this->actingAs(TestUtils::setupAdmin())->put('/products/1', $payload);

        $response->assertStatus(302);
        $this->assertDatabaseHas('products', ['name' => 'New Product']);
    }

    public function test_show_displays_product_details_and_quantity_for_client()
    {
        $client = TestUtils::setupClient();
        Category::factory()->create();
        Product::factory()->create(['id' => 1, 'category_id' => 1, 'price' => 100]);
        Discount::factory()->create(['category_id' => 1, 'user_id' => 2, 'discount' => 10]);
        Order::factory()->create(['user_id' => $client->id, 'status' => 0]);
        OrderProduct::factory()->create(['product_id' => 1, 'quantity' => 30]);
        Usage::factory()->count(3)->create();

        $response = $this->actingAs($client)
            ->session(['current_order' => 1])
            ->get('/products/1');

        $response->assertStatus(200);
        $data = $response->getOriginalContent()->getData();
        $this->assertEquals(90, $data['product']->price);
        $this->assertEquals(30, $data['product']->quantity);
        $this->assertCount(3, $data['usages']);
    }

    public function test_edit_parameters_shows_parameters()
    {
        Category::factory()->create();
        Product::factory()->create(['id' => 1, 'category_id' => 1]);
        Usage::factory()->count(3)->create();
        Parameter::factory()->count(3)->create();
        ProductParameter::factory()->create(['product_id' => 1, 'parameter_id' => 1, 'usage_id' => 1, 'value' => 2]);
        ProductParameter::factory()->create(['product_id' => 1, 'parameter_id' => 2, 'usage_id' => 1, 'value' => 3]);
        ProductParameter::factory()->create(['product_id' => 1, 'parameter_id' => 1, 'usage_id' => 2, 'value' => 4]);

        $response = $this->actingAs(TestUtils::setupAdmin())->get('/products/1/parameters?usage=1');

        $response->assertStatus(200);
        $data = $response->getOriginalContent()->getData();
        $this->assertCount(3, $data['parameters']);
        $this->assertEquals(2, $data['parameters'][0]->productValue);
        $this->assertEquals(3, $data['parameters'][1]->productValue);
        $this->assertEquals(null, $data['parameters'][2]->productValue);
    }

    public function test_updates_parameters()
    {
        Category::factory()->create();
        Product::factory()->create(['id' => 1, 'category_id' => 1]);
        Usage::factory()->count(3)->create();
        Parameter::factory()->count(3)->create();
        ProductParameter::factory()->create(['product_id' => 1, 'parameter_id' => 1, 'usage_id' => 1, 'value' => 2]);
        $payload = [
            '1' => '10',
            '2' => '20'
        ];

        $response = $this->actingAs(TestUtils::setupAdmin())->put('/products/1/usages/1/parameters', $payload);

        $response->assertStatus(302);
        $this->assertDatabaseCount('product_parameters', 2);
        $this->assertDatabaseHas('product_parameters', ['product_id' => 1, 'usage_id' => 1, 'parameter_id' => 1, 'value' => 10]);
        $this->assertDatabaseHas('product_parameters', ['product_id' => 1, 'usage_id' => 1, 'parameter_id' => 2, 'value' => 20]);
    }
}
