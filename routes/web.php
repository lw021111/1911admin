<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

# 权限节点添加
Route::any('/powerNode/add' , 'Admin\PowerNodeController@powerNodeAdd' );

# 权限列表
Route::any('/powerNode/list' , 'Admin\PowerNodeController@powerNodeList' );

Route::prefix('admin')->group(function(){
   Route::get('index','Admin\AdminController@index');
});

//a
Route::prefix('admin')->group(function(){
	Route::get('/','Admin\IndexController@index');
	Route::get('/create','Admin\IndexController@create');
	Route::get('/createdo','Admin\IndexController@createdo');
});

