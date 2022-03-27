<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usage extends Model
{
    protected $table = 'usages';
    public $primaryKey = 'id';
    public $timeStamps = true;
}
