<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class GoodsSkuModel extends Model
{
    //

    public $table = 'shop_goods_sku';

    public $timestamps = false;

    public $primaryKey = 'sku_id';
}
