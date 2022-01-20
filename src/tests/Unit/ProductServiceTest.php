<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Product;
use App\Category;
use App\Subcategory;
use App\Services\ProductService;
use App\Services\SearchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use stdClass;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testSearchReturnsProducts()
    {
        $mockSearchService = new MockSearchService();
        $productService = new ProductService($mockSearchService);
        $result = $productService->getProductsBySearch("query");
        $this->assertCount(3, $result);
    }

    public function testGetProductReturnsProduct()
    {
        $this->prepareData();
        factory(Product::class)->create(['id' => 1]);
        $mockSearchService = new MockSearchService();
        $productService = new ProductService($mockSearchService);
        $result = $productService->getProductWithRelatedProducts(1);
        $this->assertNotEmpty($result);
        $this->assertCount(2, $result);
        $this->assertTrue($result[0]->id == 1);
    }

    private function prepareData() {
        factory(Category::class)->create(['id' => 1]);
        factory(Subcategory::class)->create(['id' => 1]);
    }
}

class MockSearchService extends SearchService
{
    public function searchProducts($query)
    {
        $subcategory = new stdClass;
        $subcategory->discount = 5;
        return factory(Product::class, 3)->make([
            'subcategory' => $subcategory
        ]);
    }
}
