<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Category;

class ReorderingService
{
    private $supportedTypes = ['products', 'categories'];

    public function reorderItems($request, $type, $parentId = null) 
    {
        if (!$this->isReorderTypeSupported($type)) { return; }
        $items = $this->getItemsToReorder($type, $parentId);
        foreach ($items as $item) {
            $new_position = $request->input($item->id);
            if ($new_position != null && is_numeric($new_position)){
                $item->position = $new_position;
                $item->save();
            }
        }
    }

    public function getItemsToReorder($type, $parentId) 
    {
        switch ($type) {
            case 'categories':
                return Category::where('parent_id', $parentId)->orderBy('position')->get();
            case 'products':
                return Product::where('category_id', $parentId)->orderBy('position')->get();
            default: return null;
        }
    }

    private function isReorderTypeSupported($type) 
    {
        return in_array($type, $this->supportedTypes);
    }
}
