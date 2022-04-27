<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Discount;
use App\Models\File;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Parameter;
use App\Models\Product;
use App\Models\ProductParameter;
use App\Models\RelatedProduct;
use App\Models\Usage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

    public function test_product_details_and_quantity_is_displayed_for_client()
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

    public function test_parameters_are_updated()
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

    public function test_product_is_destroyed()
    {
        $category = Category::factory()->create();
        Product::factory()->create(['id' => 1, 'category_id' => $category->id]);

        $response = $this->actingAs(TestUtils::setupAdmin())->delete('/products/1');

        $response->assertStatus(302);
        $this->assertDatabaseCount('products', 0);
    }

    public function test_related_products_are_updated()
    {
        Category::factory()->create();
        Product::factory()->count(5)->create(['category_id' => 1]);
        RelatedProduct::factory()->create(['product_id' => 1, 'related_product_id' => 2]);
        $payload = [
            'product' => '1',
            '3' => 'selected',
            '5' => 'selected',
        ];

        $response = $this->actingAs(TestUtils::setupAdmin())->post('/related-products', $payload);

        $response->assertStatus(302);
        $this->assertDatabaseCount('related_products', 2);
        $this->assertDatabaseHas('related_products', ['product_id' => 1, 'related_product_id' => 3]);
        $this->assertDatabaseHas('related_products', ['product_id' => 1, 'related_product_id' => 5]);
    }

    public function test_related_products_are_displayed_in_edit()
    {
        Category::factory()->create();
        Product::factory()->count(5)->create(['category_id' => 1]);
        RelatedProduct::factory()->create(['product_id' => 1, 'related_product_id' => 2]);

        $response = $this->actingAs(TestUtils::setupAdmin())->get('/related-products/1/edit');

        $response->assertStatus(200);
        $data = $response->getOriginalContent()->getData();
        $this->assertCount(5, $data['products']);
        $this->assertCount(1, $data['relatedProducts']);
    }

    public function test_product_file_stored()
    {
        Category::factory()->create();
        Product::factory()->create();
        $file = UploadedFile::fake()->image('test.png');
        $payload = ['product_id' => 1, 'product_file' => $file];

        $response = $this->actingAs(TestUtils::setupAdmin())->post('/product-files', $payload);

        $response->assertStatus(302);
        $this->assertDatabaseCount('product_files', 1);
        $databaseFile = File::first();
        $this->assertEquals($databaseFile->name, 'test.png');
        Storage::disk('public')->assertExists('uploads/' . $databaseFile->file_name . '.' . $databaseFile->file_extension);
    }

    public function test_product_file_name_is_updated()
    {
        Category::factory()->create();
        $product = Product::factory()->create();
        $file = File::factory()->create(['id' => 1]);
        $product->files()->attach($file);
        $payload = ['name' => 'Test Name'];

        $response = $this->actingAs(TestUtils::setupAdmin())->put('/product-files/1', $payload);

        $response->assertStatus(302);
        $this->assertDatabaseCount('files', 1);
        $this->assertDatabaseHas('files', ['name' => 'Test Name']);
    }

    public function test_product_file_edit_is_displayed()
    {
        Category::factory()->create();
        $product = Product::factory()->create();
        $file = File::factory()->create(['id' => 1, 'name' => 'TestName']);
        $product->files()->attach($file);

        $response = $this->actingAs(TestUtils::setupAdmin())->get('/product-files/1/edit');

        $response->assertStatus(200);
        $data = $response->getOriginalContent()->getData();
        $this->assertEquals('TestName', $data['productFile']->name);
    }
}
