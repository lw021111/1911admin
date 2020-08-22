<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class BasicAttrModel extends Model
{
    //

    public $table = 'shop_basic_attr';

    public $timestamps = false;

    public $primaryKey = 'basic_attr_id';
}
