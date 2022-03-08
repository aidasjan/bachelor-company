<?php

namespace App\Services;

use App\Models\Product;

class SearchService
{
    public function searchProducts($query) 
    {
        $sanitized_query = preg_replace("/[^A-Za-z0-9 ]/", '', $query);
        if (strlen($sanitized_query) == 0) {
            return [];
        }
        $products = Product::whereRaw("MATCH (code, name, description) AGAINST (? IN BOOLEAN MODE)", $sanitized_query)
            ->take(config('custom.search.results_limit'))->get();
        return $products;
    }
}