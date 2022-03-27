<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProductsExport implements FromCollection
{
    public function collection()
    {
        $header = collect([['Code', 'Name', 'Price', 'Currency', 'Unit', 'Category']]);
        $data = Product::all()->map(function ($product) {
            return [
                $product->code,
                $product->name,
                $product->price,
                $product->currency,
                $product->unit,
                $product->category->code,
            ];
        });
        return $header->merge($data);
    }
}