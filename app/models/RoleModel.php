<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class RoleModel extends Model
{
    //

    public $table = 'rbac_role';

    public $timestamps = false;

    public $primaryKey = 'role_id';
}
