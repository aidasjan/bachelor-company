<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Parameter;
use App\Models\Product;
use App\Models\ProductParameter;
use App\Models\Usage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecommendationsTest extends TestCase
{
    use RefreshDatabase;

    public function test_recommendation_parameters_are_displayed()
    {
        $this->setupRecommendationsData();

        $response = $this->get('/recommendations/parameters?usage=1');

        $response->assertStatus(200);
        $data = $response->getOriginalContent()->getData();
        $this->assertCount(2, $data['parameters']);
    }

    public function test_recommended_products_are_displayed()
    {
        $this->setupRecommendationsData();

        $response = $this->get('/recommendations/1?1=5&2=15');

        $response->assertStatus(200);
        $data = $response->getOriginalContent()->getData();
        $this->assertCount(1, $data['products']);
    }

    public function test_unsuitable_products_are_not_displayed()
    {
        $this->setupRecommendationsData();

        $response = $this->get('/recommendations/1?1=15&2=15');

        $response->assertStatus(200);
        $data = $response->getOriginalContent()->getData();
        $this->assertCount(0, $data['products']);
    }

    private function setupRecommendationsData() {
        Category::factory()->create();
        Product::factory()->create();
        Parameter::factory()->count(2)->create();
        Usage::factory()->create();
        ProductParameter::factory()->create(['product_id' => 1, 'parameter_id' => 1, 'usage_id' => 1, 'value' => 10]);
        ProductParameter::factory()->create(['product_id' => 1, 'parameter_id' => 2, 'usage_id' => 1, 'value' => 20]);
    }
}
