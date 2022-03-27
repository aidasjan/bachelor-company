<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;

class CategoriesExport implements FromCollection
{
    public function collection()
    {
        $header = collect([['Code', 'Name', 'Name RU', 'Discount', 'Parent Category']]);
        $data = Category::all()->map(function ($category) {
            return [
                $category->code,
                $category->name,
                $category->name_ru,
                strval($category->discount),
                $category->parentCategory ? $category->parentCategory->code : null,
            ];
        });
        return $header->merge($data);
    }
}