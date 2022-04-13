<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Company extends Model
{
    use HasFactory;

    protected $connection = 'mysql_gateway';

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        if (App::runningUnitTests()) {
            $this->setConnection('sqlite');
        }
    }
}
