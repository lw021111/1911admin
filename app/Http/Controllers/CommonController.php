<?php

namespace App\Http\Controllers;

use App\models\AdminModel;
use App\models\BrandModel;
use App\models\CategoryModel;
use Illuminate\Routing\Controller as BaseController;

class CommonController extends BaseController
{

    /**
     * 检查管理员的账号状态
     */
    public function  checkAdminStatus( AdminModel $admin_obj )
    {

        if( empty( $admin_obj ) ){
            return $this -> fail('该账号不存在');
        }

        $map = [
            '0' => '账号状态不正常，请联系超级管理员处理',
            '1' => '账号还没有审核通过',
            '2' => '账号被锁定，请联系超级管理员解锁'
        ];

        #状态是3的时候，是正常
        if( $admin_obj -> status != 3 ){
            return $this -> fail( $map[$admin_obj -> status] );
        }

        return $this -> success();
    }

    /**
     * 失败的返回
     */
    public function fail( $msg = '' , $status = 1 , $data = [] )
    {
        return [
            'status' => $status ,
            'msg' => $msg,
            'data' => $data
        ];
    }


    /**
     * 失败的返回
     */
    public function success( $data = [] , $status = 200 , $msg = 'success'   )
    {
        return [
            'status' => $status ,
            'msg' => $msg,
            'data' => $data
        ];
    }

    /**
     * 获取分类数据 - 层级
     */
    public function getCategoryLevel( $is_show_level = 1  )
    {
        $cate_where = [
            [
                'status' , '=' , 1
            ]
        ];

        $cate_model = new CategoryModel();

        $cate_list_obj = $cate_model -> where( $cate_where ) -> get();

        return $this -> showTree( $cate_list_obj , 0 , $is_show_level  );

    }

    /**
     * 展示对应的分类
     */
    public function showTree( $cate_list_obj , $pid = 0 , $is_level = 1 ){

        $tree = [];
        foreach( $cate_list_obj as $k => $v ){
            if( $pid == $v -> pid ){

                #  层级数据的
                if( $is_level == 1 ){
                    $v['son'] = $this -> showTree( $cate_list_obj , $v -> cate_id );
                    $tree[] = $v -> toArray();
                }else{
                    # 非层级的数据
                    $tree[] = $v -> toArray();
                    $son = $this -> showTree( $cate_list_obj , $v -> cate_id , 0 );
                    $tree = array_merge( $tree , $son );
                }
            }
        }
        return $tree;
    }

    /**
     * 获取品牌的列表
     */
    protected function getBrandList()
    {
        return BrandModel::where( [ ['status','=',1] ] ) -> get();
    }


}










