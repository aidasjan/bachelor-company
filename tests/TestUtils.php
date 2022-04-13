<?php

namespace Tests;

use App\Models\Category;
use App\Models\Company;
use App\Models\Product;
use App\Models\User;

class TestUtils
{
    public static function setupAdmin()
    {
        Company::factory()->create(['id' => 1]);
        $admin = User::factory()->create(['id' => 1, 'company_id' => 1, 'role' => encrypt('admin')]);
        return $admin;
    }

    public static function setupClient()
    {
        $client = User::factory()->create(['id' => 2, 'role' => encrypt('client')]);
        return $client;
    }

    public static function createProductsWithCategory($count)
    {
        $category = Category::factory()->create();
        $products = Product::factory()->count($count)->create([ 'category_id' => $category->id ]);
        return $products;
    }
}
