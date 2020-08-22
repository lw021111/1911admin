<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;

class IndexController extends CommonController
{
    /**
     * 首页
     * @return mixed
     */
    public function Index(){
        return view('admin/index');
    }
}
