<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //layui 后台布局
    public function index(){

        return view('admin.admin');
    }

}
