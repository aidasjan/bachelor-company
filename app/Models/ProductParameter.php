<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductParameter extends Model
{
    protected $table = 'product_parameters';
    public $primaryKey = 'id';
    public $timeStamps = true;

    public function product() {
        return $this->belongsTo('App\Models\Product');
    }

    public function usage() {
        return $this->belongsTo('App\Models\Usage');
    }

    public function parameter() {
        return $this->belongsTo('App\Models\Parameter');
    }
}
