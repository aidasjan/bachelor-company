<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usage extends Model
{
    protected $table = 'usages';
    public $primaryKey = 'id';
    public $timeStamps = true;

    public function productParameters() {
        return $this->belongsToMany('App\Models\ProductParameter', 'product_parameters');
    }

    public function safeDelete() {
        $this->productParameters()->detach();
        $this->delete();
    }
}
