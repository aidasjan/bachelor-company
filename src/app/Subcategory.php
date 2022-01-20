<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    protected $table = 'subcategories';
    public $primaryKey = 'id';
    public $timeStamps = true;

    public function getNameAttribute($value) {
        if (app()->getLocale() == 'ru'){
            $value = $this->name_ru;
        }
        return $value;
    }

    public function category() {
        return $this->belongsTo('App\Category', 'category_id');
    }

    public function products() {
        return $this->hasMany('App\Product', 'subcategory_id');
    }

    public function files() {
        return $this->belongsToMany('App\File', 'subcategory_files');
    }

    public function safeDelete() {
        $products = $this->products;
        foreach ($products as $product) {
            $product->safeDelete();
        }
        $files = $this->files;
        foreach ($files as $file) {
            $file->safeDelete();
        }
        $this->delete();
    }

    public function getDisplayUrl() {
        return '/categories'.'/'.$this->category->code.'/'.$this->code;
    }
}
