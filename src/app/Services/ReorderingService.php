<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use App\Product;
use App\Subcategory;
use App\Category;

class ReorderingService
{
    private $supported_types = ['products', 'categories', 'subcategories'];

    public function reorderItems($request, $type, $parent_id = null) 
    {
        if (!$this->isReorderTypeSupported($type)) { return; }
        $items = $this->getItemsToReorder($type, $parent_id);
        foreach ($items as $item) {
            $new_position = $request->input($item->id);
            if ($new_position != null && is_numeric($new_position)){
                $item->position = $new_position;
                $item->save();
            }
        }
    }

    public function getItemsToReorder($type, $parent_id) 
    {
        switch ($type) {
            case 'categories':
                return Category::orderBy('position')->get();
            case 'subcategories':
                return Subcategory::where('category_id', $parent_id)->orderBy('position')->get();
            case 'products':
                return Product::where('subcategory_id', $parent_id)->orderBy('position')->get();
            default: return null;
        }
    }

    private function isReorderTypeSupported($type) 
    {
        return in_array($type, $this->supported_types);
    }
}
