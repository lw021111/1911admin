<?php

namespace App\Providers;

use App\models\PowerNodeModel;
use Illuminate\Routing\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $route = '/'. request() ->path();

        //视图间共享数据
        $power_node = $this -> getPowerNode();

        #第一个参数是模板，多个模板['aa','bb'] use中是传到闭包中的变量
        view()->composer('*',function($view)use($power_node,$route){
            $view->with(
                array(
                    'node_list'=> $power_node,
                    'route' => $route
                )
            );
        });
    }

    /**
     * @return array
     */
    public function getPowerNode()
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

        return $all_node;
    }
}
