<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;

class ReorderingService
{
    private $supported_types = ['products', 'categories'];

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
                return Category::where('parent_id', $parent_id)->orderBy('position')->get();
            case 'products':
                return Product::where('category_id', $parent_id)->orderBy('position')->get();
            default: return null;
        }
    }

    private function isReorderTypeSupported($type) 
    {
        return in_array($type, $this->supported_types);
    }
}
