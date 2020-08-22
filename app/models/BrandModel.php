<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class BrandModel extends Model
{
    //

    public $table = 'shop_brand';

    public $timestamps = false;

    public $primaryKey = 'brand_id';
}
