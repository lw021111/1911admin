<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class AdminRoleModel extends Model
{
    //

    public $table = 'rbac_admin_role_relation';

    public $timestamps = false;

    public $primaryKey = 'id';
}
