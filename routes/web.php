<?php

use Illuminate\Support\Facades\Route;

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

# 需要权限控制的路由


    # 权限节点添加
    Route::any('/powerNode/add' , 'Admin\PowerNodeController@powerNodeAdd' );

    # 权限列表
    Route::any('/powerNode/list' , 'Admin\PowerNodeController@powerNodeList' );


    # 角色添加
    Route::any('/role/add' , 'Admin\RoleController@roleAdd' );

    # 角色列表
    Route::any('/role/list' , 'Admin\RoleController@roleList' );


    # 管理员添加
    Route::any('/admin/add' , 'Admin\AdminController@adminAdd' );

    # 管理员列表
    Route::any('/admin/list' , 'Admin\AdminController@adminList' );

    # 基本属性的添加
    Route::any('Attr/basicAttr' , 'Admin\AttrController@basicAttr' );

    # 基本属性列表
    Route::any('Attr/basicAttrList', 'Admin\AttrController@basicAttrList' );

    # 销售属性的添加
    Route::any('Attr/saleAttr' , 'Admin\AttrController@saleAttr' );

    # 销售属性列表
    Route::any('Attr/saleAttrList', 'Admin\AttrController@saleAttrList' );

    # 商品列表
    Route::any('goods/goodsList', 'Admin\GoodsController@goodsList' );

    # 商品列表
    Route::any('goods/goodsAdd', 'Admin\GoodsController@goodsAdd' );



# 不需要权限控制的路由，默认走web中间件即可
Route::middleware( [ 'web']) -> group(function(){
    # 登陆页面
    Route::any('/login' , 'Admin\AdminController@login' );

    # 登陆页面
    Route::any('/logout' , 'Admin\AdminController@logout' );

    Route::any('/' , 'Admin\IndexController@Index' );

    Route::any('showBasicAttr' , 'Admin\AttrController@showBasicAttr' );

    Route::any('showSaleAttr' , 'Admin\AttrController@showSaleAttr' );

    Route::any('uploadGoodsImg' , 'Admin\GoodsController@uploadGoodsImg' );

    Route::any('jobTest' , 'Admin\MsgController@test' );

});

























