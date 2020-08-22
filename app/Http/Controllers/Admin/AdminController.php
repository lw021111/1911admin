<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CommonController;
use App\models\AdminRoleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\models\AdminModel;

/**
 * 管理员管理模块
 * Class AdminController
 * @package App\Http\Controllers\Admin
 */
class AdminController extends CommonController
{

    /**
     * 管理员登陆
     */
    public function login( Request $request )
    {
        if( $request -> ajax() && $request -> method() == 'POST' )
        {
            # 接受参数，去登陆
            $admin_name = $request -> post('admin_name')??'';
            if( empty( $admin_name ) )
            {
                return $this -> fail('请输入你的账号');
            }
            $password = $request -> post('password')??'';
            if( empty( $password ) )
            {
                return $this -> fail('请输入你的密码');
            }

            $where = [
                ['admin_name' , '=' , $admin_name]
            ];

            $admin_model = new AdminModel();

            $admin_obj = $admin_model -> where( $where ) -> first();

            if( empty( $admin_obj ) ){
                return $this -> fail('该账号不存在');
            }

            # 判断密码是否正确
            if( $admin_obj -> admin_pwd !=  md5( $password .$admin_obj->salt ) )
            {
                return $this -> fail('账号密码不匹配');
            }

            $check = $this -> checkAdminStatus( $admin_obj );

            if( $check['status'] != 200 ){
                return $check;
            }

            # 记录成功，记录用户信息
            $request -> session() -> put( 'admin_info' , $admin_obj -> toArray() );

            return $this -> success();
        }
        return view('admin.login');
    }

    /**
     * 退出登陆
     */
    public function logout( Request $request )
    {
        $request -> session() -> forget('admin_info');

        return redirect('login');

    }
    /**
     * 管理员列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function adminList(){

        return view( 'admin.list' );
    }

    /**
     * 管理员添加
     */
    public function AdminAdd( Request $request ){

        if( $request -> ajax() && $request -> method() == 'POST' )
        {
            # 对参数进行校验
            $admin_name = $request -> post('admin_name')??'';
            if( empty( $admin_name ) ){
                return $this -> fail('管理员名字不能为空');
            }
            $real_name = $request -> post('real_name')??'';
            if( empty( $real_name ) ){
                return $this -> fail('管理员真实名字不能为空');
            }
            $pwd = $request -> post('pwd')??'';
            if( empty( $pwd ) ){
                return $this -> fail('密码不能为空');
            }
            $phone = $request -> post('phone')??'';
            if( empty( $phone ) ){
                return $this -> fail('手机号不能为空');
            }
            $email = $request -> post('email')??'';
            if( empty( $email ) ){
                return $this -> fail('管理员邮箱不能为空');
            }

            $admin_type = $request -> post('admin_type')??2;

            $role = $request ->post('role')??[];
            if( $admin_type  == 2 ){
                if( empty( $role ) ){
                    return $this -> fail('请选择管理员对应的角色');
                }
            }

            # 判断管理员密码不能为空
            $where = [
                [ 'admin_name' , '=' ,$admin_name  ]
            ];

            $admin_model = new AdminModel();

            if( $admin_model -> where( $where ) -> count() > 0 )
            {
                return $this -> fail('管理员名字重复，请确认～');
            }


            $salt = rand(1000,9999);
            $now = time();
            try{

                DB::beginTransaction();

                $admin_new_model = new AdminModel();
                $admin_new_model -> admin_name = $admin_name;
                $admin_new_model -> admn_phone = $phone;
                $admin_new_model -> admin_email = $email;
                $admin_new_model -> admin_pwd = md5($pwd.$salt);
                $admin_new_model -> salt = $salt;
                $admin_new_model -> status = 1;
                $admin_new_model -> ctime = $now;
                $admin_new_model -> admin_type = $admin_type;

                # 保存管理员
                $admin_new_model -> save();
                $admin_id = $admin_new_model -> admin_id;
                if( !$admin_id  )
                {
                    throw new \Exception('管理员表写入失败');
                }

                # 写入管理员和角色的关联关系
                if( $admin_type == 2 ){
                    foreach( $role as $k => $v )
                    {
                        $admin_role_model = new AdminRoleModel();
                        $admin_role_model -> admin_id = $admin_id;
                        $admin_role_model -> role_id = $v;
                        if( !$admin_role_model -> save() ){
                            throw new \Exception('关联表写入失败');
                        }
                    }
                }

                # 提交事务
                DB::commit();

                return $this -> success();

            }catch ( \Exception $e ){

                # 回滚事务
                DB::rollBack();

                $msg = $e ->getMessage();

                return $this -> fail( $msg );

            }


        }

        # 需要取出来现在所有的角色和对应的权限

        $role_where = [
            [
                'r.status' , '=' , 1
            ]
        ];

        $role_list = DB::table('rbac_role as r')
            -> where( $role_where )
            -> join( 'rbac_role_power_relation as rpnr', 'r.role_id' , '=' , 'rpnr.role_id' )
            -> join( 'rbac_power_node as rpn', 'rpnr.power_node_id' , '=' , 'rpn.power_node_id' )
            -> get()
            -> toArray();
        $role_list = json_decode(json_encode( $role_list ) , true  );

        $role_new = [];

        foreach( $role_list as $k => $v ){
            if( $v['power_node_level'] == 1 )
            {
                if( !isset($role_new[$v['role_id']])){
                    $role_new[$v['role_id']] = $v;
                }else{
                    $role_new[$v['role_id']]['power_list'][] = $v;
                }
            }else{
                $role_new[$v['role_id']]['power_list'][] = $v;
            }
        }
//        echo '<hr/>';
//        print_r($role_new);
//        exit;
        return view( 'admin.add' , [
            'role_list' => $role_new
        ]);
    }
}










