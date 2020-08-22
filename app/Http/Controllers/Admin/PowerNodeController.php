<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CommonController;
use App\models\PowerNodeModel;
use Illuminate\Http\Request;

/**
 * 权限节点管理模块
 * Class PowerNodeController
 * @package App\Http\Controllers\Admin
 */
class PowerNodeController extends CommonController
{
    /**
     * 权限节点添加
     * @return mixed
     */
    public function powerNodeAdd( Request $request ){

        # 判断是否是post，如果是post 说明是提交数据

        $power_node_model = new PowerNodeModel();

        if( $request -> method() == "POST"  )
        {
            $power_node_model -> power_node_name =  $request -> post('node_name');
            if( empty( $request -> post('pid') ) ){
                $power_node_model -> power_node_level =  1;
            }else{
                $power_node_model -> power_node_level =  2;
            }
            $power_node_model -> power_node_pid = $request -> post('pid');
            $power_node_model -> power_node_url= $request -> post('path');

            $power_node_model -> status = $request -> post('status');
            $power_node_model -> ctime = time();

            if( $power_node_model -> save() ){
             echo "<script>alert('添加成功');location='/powerNode/list'</script>";
            }else{

            }
        }

        # 查询出系统现有的父级节点

        # 查询所有一级的节点
        $where = [
            [ 'power_node_level' , '=' , 1],
            [ 'status'  , '=' , 1 ]
        ];

        //左侧列表查询
        $power_node_list = $power_node_model -> where( $where ) -> get();
//        dd($power_node_list);

//        dd( $power_node_list );
        return view('powernode/add' , [
            'power_list' => $power_node_list
        ]);
    }

    /**
     * 权限节点的列表
     */
    public function powerNodeList( Request $request ){

        $sql = PowerNodeModel::paginate(5);



        return view('powernode.list',['sql'=>$sql]);
    }
}










