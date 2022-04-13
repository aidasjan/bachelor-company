<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\File;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\TestUtils;

class CategoriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_categories_are_displayed()
    {
        $testRecords = Category::factory()->count(5)->create();

        $response = $this->get('/');

        $response->assertStatus(200);
        $data = $response->getOriginalContent()->getData();
        $this->assertCount(5, $data['categories']);
        $this->assertEquals($data['categories'][0]->name, $testRecords[0]->name);
    }

    public function test_new_category_is_saved()
    {
        $admin = TestUtils::setupAdmin();
        $payload = [
            'code' => 'Code',
            'name' => 'Category',
            'name_ru' => 'Category',
            'discount' => 10
        ];

        $response = $this->actingAs($admin)->post('/categories', $payload);

        $response->assertStatus(302);
        $this->assertDatabaseHas('categories', ['name' => 'Category']);
    }

    public function test_category_is_updated()
    {
        $admin = TestUtils::setupAdmin();
        Category::factory()->create(['id' => 1]);
        $payload = [
            'code' => 'Code',
            'name' => 'New Category',
            'name_ru' => 'Category',
            'discount' => 10
        ];

        $response = $this->actingAs($admin)->put('/categories/1', $payload);

        $response->assertStatus(302);
        $this->assertDatabaseHas('categories', ['name' => 'New Category']);
    }

    public function test_category_is_destroyed()
    {
        $admin = TestUtils::setupAdmin();
        Category::factory()->create();

        $response = $this->actingAs($admin)->delete('/categories/1');

        $response->assertStatus(302);
        $this->assertDatabaseCount('categories', 0);
    }

    public function test_category_edit_has_category_data()
    {
        $admin = TestUtils::setupAdmin();
        $category = Category::factory()->create();

        $response = $this->actingAs($admin)->get('/categories/1/edit');

        $response->assertStatus(200);
        $data = $response->getOriginalContent()->getData();
        $this->assertEquals($category->name, $data['category']->name);
    }

    public function test_category_without_products_shows_subcategories()
    {
        Category::factory()->create(['id' => 1, 'code' => 'test']);
        Category::factory()->create(['parent_id' => 1]);

        $response = $this->get('/categories/test');

        $response->assertStatus(200);
        $data = $response->getOriginalContent()->getData();
        $this->assertCount(1, $data['childCategories']);
    }

    public function test_category_with_products_shows_products()
    {
        Category::factory()->create(['id' => 1, 'code' => 'test']);
        Product::factory()->create(['category_id' => 1]);

        $response = $this->get('/categories/test');

        $response->assertStatus(200);
        $data = $response->getOriginalContent()->getData();
        $this->assertCount(1, $data['products']);
    }

    public function test_category_image_stored()
    {
        $admin = TestUtils::setupAdmin();
        Category::factory()->create(['id' => 1]);
        $file = UploadedFile::fake()->image('test.png');
        $payload = ['category_file' => $file];

        $response = $this->actingAs($admin)->post('/categories/1/images', $payload);

        $response->assertStatus(302);
        $this->assertDatabaseCount('category_files', 1);
        $databaseFile = File::first();
        $this->assertEquals($databaseFile->name, 'test.png');
        Storage::disk('public')->assertExists('uploads/' . $databaseFile->file_name . '.' . $databaseFile->file_extension);
    }
}
