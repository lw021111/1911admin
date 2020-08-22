<?php

namespace App\Http\Middleware;

use App\models\PowerNodeModel;
use Closure;
use Illuminate\Support\Facades\DB;

class RbacMiddleWare
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $admin_info = $this->getAdminInfo();

        $route = $request->route()->uri();

//        var_dump($route);exit;
        # 没有登陆跳转到Login
        if (empty($admin_info) && $route != 'login') {
            return redirect('login');
        }

        # 取出对应用户的权限信息
        $power_list = $this->getAdminPower(  );

        view()->composer('*',function($view)use($power_list,$route){
            $view->with(
                array(
                    'node_list'=> $power_list,
                    'route' => $route
                )
            );
        });

        # 判断用户是否有权限访问对应的方法
        if( $this -> checkAdminPower() ){
            return $next($request);
        }else{
            return response() -> view('admin.nopower');
        }

    }

    /**
     * 检查用户是否有权限访问对应的方法
     * @return bool
     */
    public function checkAdminPower()
    {

        $route = request() -> route() ->uri();

        $power_list = $this->getAdminPower( 2 );

        $all_power = [];
        foreach( $power_list as $k => $v ){
            $all_power[] = $v['power_node_url'];
        }

        if( in_array( '/' . $route , $all_power ) )
        {
            return true;
        }else{
            return false;
        }
    }

    /**
     * 获取登陆的管理员的信息
     * @return mixed
     */
    private function getAdminInfo()
    {
        return request()->session()->get('admin_info');
    }

    /**
     * 获取管理员的权限
     */
    public function getAdminPower( $is_show_level = 1 )
    {
        $admin_info = $this->getAdminInfo();


        if( $admin_info['admin_type'] == 1 ){
            $this_admin_power = $this -> getSuperAdminPower( $is_show_level );
        }else{
            $this_admin_power = $this -> getAdminPowerByAdminId( $admin_info['admin_id'], $is_show_level );
        }

        return $this_admin_power;
    }

    /**
     * 获取普通管理员的权限
     */
    public function getAdminPowerByAdminId( $admin_id , $is_show_level = 1 )
    {

        $admin_power = DB::table('rbac_admin as ra')
            ->select('ra.admin_id',
                'ra.admin_name',
                'rar.role_id',
                'rr.role_name',
                'rrpr.power_node_id',
                'rpn.*'
            )->join('rbac_admin_role_relation as rar', 'rar.admin_id', '=', 'ra.admin_id')
            ->join('rbac_role as rr', 'rr.role_id', '=', 'rar.role_id')
            ->join('rbac_role_power_relation as rrpr', 'rrpr.role_id', '=', 'rr.role_id')
            ->join('rbac_power_node as rpn', 'rpn.power_node_id', '=', 'rrpr.power_node_id')
            -> where([ ['ra.admin_id' , '=' , $admin_id] ])
            ->get()
            ->toArray();
        $admin_power_list = json_decode( json_encode( $admin_power )  , true  );

        $all_node = [];
        foreach( $admin_power_list as $k => $v ){
            if( $v['power_node_pid'] == 0 ){
                $all_node[$v['power_node_id']] = $v;
            }else{
                $all_node[$v['power_node_pid']]['son'][] = $v;
            }
        }
        // 是否返回层级的节点 1 层级 2、非层级
        if( $is_show_level == 1 ){
            return $all_node;
        }else{
            return $admin_power_list;
        }

    }

    /**
     * 获取超级管理员的权限
     * @return array
     */
    public function getSuperAdminPower( $is_show_level = 1 )
    {
        $power_node_model = new PowerNodeModel();

        $where = [
            [ 'status' , '=' , 1 ]
        ];
        $obj = $power_node_model -> where( $where ) -> get();

        $power_node_list = collect( $obj ) -> toArray();

        $all_node = [];
        foreach( $power_node_list as $k => $v ){
            if( $v['power_node_pid'] == 0 ){
                $all_node[$v['power_node_id']] = $v;
            }else{
                $all_node[$v['power_node_pid']]['son'][] = $v;
            }
        }

        // 是否返回层级的节点 1 层级 2、非层级
        if( $is_show_level == 1 ){
            return $all_node;
        }else{
            return $power_node_list;
        }
    }

}
