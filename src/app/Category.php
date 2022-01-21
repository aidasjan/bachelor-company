<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    public $primaryKey = 'id';
    public $timeStamps = true;

    public function getNameAttribute($value) {
        if (app()->getLocale() == 'ru'){
            $value = $this->name_ru;
        }
        return $value;
    }

    public function products() {
        return $this->hasMany('App\Product', 'category_id');
    }

    public function parentCategory() {
        return $this->belongsTo('App\Category', 'parent_id');
    }

    public function childCategories() {
        return $this->hasMany('App\Category', 'parent_id');
    }

    public function files() {
        return $this->belongsToMany('App\File', 'category_files');
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
