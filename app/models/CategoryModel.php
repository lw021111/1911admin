<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class CategoryModel extends Model
{
    //

    public $table = 'shop_category';

    public $timestamps = false;

    public $primaryKey = 'cate_id';
}
