<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\TestUtils;

class ExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_categories_are_exported()
    {
        Category::factory()->count(5)->create();

        $response = $this->actingAs(TestUtils::setupAdmin())->get('/export/categories');

        $response->assertStatus(200);
        $response->assertDownload('categories.xlsx');
    }

    public function test_products_are_exported()
    {
        Category::factory()->create();
        Product::factory()->count(5)->create();

        $response = $this->actingAs(TestUtils::setupAdmin())->get('/export/products');

        $response->assertStatus(200);
        $response->assertDownload('products.xlsx');
    }
}
