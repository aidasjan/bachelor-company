<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Subcategory;

class Discount extends Model
{
    protected $table = 'discounts';
    public $primaryKey = 'id';
    public $timeStamps = true;

    public function subcategory() {
        return $this->belongsTo('App\Subcategory');
    }
}
