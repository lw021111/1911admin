<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class RolePowerNodeModel extends Model
{
    //

    public $table = 'rbac_role_power_relation';

    public $timestamps = false;

    public $primaryKey = 'id';
}
