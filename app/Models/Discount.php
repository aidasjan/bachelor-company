<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $table = 'discounts';
    public $primaryKey = 'id';
    public $timeStamps = true;

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }
}
