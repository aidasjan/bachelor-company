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

    public function subcategories() {
        return $this->hasMany('App\Subcategory', 'category_id');
    }

    public function safeDelete() {
        $subcategories = $this->subcategories;
        foreach ($subcategories as $subcategory) {
            $subcategory->safeDelete();
        }
        $this->delete();
    }

    public function getDisplayUrl() {
        return '/categories'.'/'.$this->code;
    }
}
