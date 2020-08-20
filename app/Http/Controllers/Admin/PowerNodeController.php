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
                return [
                    'status' => 200,
                    'msg' => 'success'
                ];
            }else{
                return [
                    'status' => 1,
                    'msg' => 'fail'
                ];
            }
        }

        # 查询出系统现有的父级节点

        # 查询所有一级的节点
        $where = [
            [ 'power_node_level' , '=' , 1],
            [ 'status'  , '=' , 1 ]
        ];


        $power_node_list = $power_node_model -> where( $where ) -> get();

//        dd( $power_node_list );
        return view('powernode/add' , [
            'power_list' => $power_node_list
        ]);
    }

    /**
     * 权限节点的列表
     */
    public function powerNodeList( Request $request )
    {
        if( $request -> ajax() )
        {
            $power_node_model = new PowerNodeModel();

            $power_node_list = $power_node_model -> paginate( $request -> get('limit') );

            $power_node_list = collect( $power_node_list ) -> toArray();

            $count = $power_node_model -> count();

            if( !empty($power_node_list['data']) )
            {
                foreach( $power_node_list['data'] as $k => $v  )
                {
                    $power_node_list['data'][$k]['create_date'] = date('Y-m-d H:i:s' , $v['ctime']);
                    if( $v['status'] == 1 )
                    {
                        $power_node_list['data'][$k]['status_desc'] = '正常' ;
                    }else{
                        $power_node_list['data'][$k]['status_desc'] = '已删除' ;
                    }
                }
            }

            $list = [
                'code' => 0 ,
                'msg' => 'success',
                'count' => $count,
                'data' =>$power_node_list['data']
            ];
            return $list;
        }

        return view('powernode.list');
    }
}










