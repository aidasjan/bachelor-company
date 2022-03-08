<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $table = 'discounts';
    public $primaryKey = 'id';
    public $timeStamps = true;

    public function category() {
        return $this->belongsTo('App\Models\Category');
    }
}
