<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class SaleAttrModel extends Model
{

    public $table = 'shop_sale_attr';

    public $timestamps = false;

    public $primaryKey = 'sale_id';

    public function saleAttrValue()
    {
        # hasone 一对一 关系  hasmany 一对多
        return $this -> hasMany('App\models\SaleValueModel','sale_attr_id','sale_id');
    }
}
