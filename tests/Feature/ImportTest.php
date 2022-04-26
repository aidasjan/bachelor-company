<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Tests\TestUtils;

class ImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_are_imported()
    {
        Category::factory()->create(['code' => 'test-category']);
        $importFile = new UploadedFile(
            'tests/Files/products.xlsx',
            'products.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );

        $response = $this->actingAs(TestUtils::setupAdmin())
            ->post('/import/import/products', ['import_file' => $importFile]);

        $response->assertStatus(200);
        $this->assertDatabaseCount('products', 3);
        $this->assertDatabaseHas('products', ['name' =>  'Test Product 1']);
    }

    public function test_categories_are_imported()
    {
        $importFile = new UploadedFile(
            'tests/Files/categories.xlsx',
            'products.xlsx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            null,
            true
        );

        $response = $this->actingAs(TestUtils::setupAdmin())
            ->post('/import/import/categories', ['import_file' => $importFile]);

        $response->assertStatus(200);
        $this->assertDatabaseCount('categories', 2);
        $this->assertDatabaseHas('categories', ['name' =>  'Test Category 1']);
    }
}
