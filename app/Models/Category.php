<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    public $primaryKey = 'id';
    public $timeStamps = true;

    use HasFactory;

    public function getNameAttribute($value) {
        if (app()->getLocale() == 'ru'){
            $value = $this->name_ru;
        }
        return $value;
    }

    public function products() {
        return $this->hasMany('App\Models\Product', 'category_id');
    }

    public function parentCategory() {
        return $this->belongsTo('App\Models\Category', 'parent_id');
    }

    public function childCategories() {
        return $this->hasMany('App\Models\Category', 'parent_id');
    }

    public function files() {
        return $this->belongsToMany('App\Models\File', 'category_files');
    }

    public function safeDelete() {
        $products = $this->products;
        foreach ($products as $product) {
            $product->safeDelete();
        }
        $this->delete();
    }

    public function getDisplayUrl() {
        return '/categories'.'/'.$this->code;
    }
}
