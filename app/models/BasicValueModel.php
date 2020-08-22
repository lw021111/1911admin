<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class BasicValueModel extends Model
{
    //

    public $table = 'shop_basic_value';

    public $timestamps = false;

    public $primaryKey = 'basic_value_id';
}
