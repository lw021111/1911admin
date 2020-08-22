<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CommonController;
use App\models\BasicAttrModel;
use App\models\BasicValueModel;
use App\models\SaleAttrModel;
use App\models\SaleValueModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * 属性管理模块
 * Class AdminController
 * @package App\Http\Controllers\Admin
 */
class AttrController extends CommonController
{
    /**
     * 基本属性列表
     */
    public function basicAttrList(){

        return view('attr.basiclist');
    }

    /**
     * 基本属性添加
     */
    public function basicAttr( Request $request ){

        if( $request -> ajax() && $request -> method() == 'POST' )
        {

            # 添加基本属性
            try{
                DB::beginTransaction();

                # 1、先写入属性的分类数据
                $cate_list = $request -> post('cate');
                $cate_id = $request -> post('cate_id');
                $attr_list = $request -> post('attr');
                if( empty( $attr_list ) )
                {

                    throw new \Exception('属性不能为空');
                }
                $value_list = $request ->post('value');
                $now = time();

                foreach( $cate_list as $k => $v ){
                    $basic_model = new BasicAttrModel();
                    $basic_model -> category_id = $cate_id;
                    $basic_model -> basic_name = $v;
                    $basic_model -> is_show_notice = 0;
                    $basic_model -> notice = 0;
                    $basic_model -> basic_level = 1;
                    $basic_model -> basic_attr_pid = 0;
                    $basic_model -> status = 1;
                    $basic_model -> ctime = $now;
                    if( $basic_model -> save() ){
                        $attr_cate_id = $basic_model -> basic_attr_id;
                    }else{
                        throw new \Exception('属性分类写入失败');
                    }

                    # 循环写入属性
                    # 获取属性分类对应的属性
                    foreach( $attr_list[$k] as $key => $value ){
                        $basic2_model = new BasicAttrModel();
                        $basic2_model -> category_id = $cate_id;
                        $basic2_model -> basic_name = $value;
                        $basic2_model -> is_show_notice = 0;
                        $basic2_model -> notice = 0;
                        $basic2_model -> basic_level = 2;
                        $basic2_model -> basic_attr_pid = $attr_cate_id;
                        $basic2_model -> status = 1;
                        $basic2_model -> ctime = $now;
                        if( $basic2_model -> save() ){
                            $attr_id = $basic2_model -> basic_attr_id;
                        }else{
                            throw new \Exception('属性写入失败');
                        }

                        # 写入属性值的数据
                        if( isset( $value_list[$k][$key] ) ){
                            foreach( $value_list[$k][$key] as $kk => $vv ) {
                                $value_model = new BasicValueModel();
                                $value_model -> basic_attr_id = $attr_id;
                                $value_model -> category_id = $cate_id;
                                $value_model -> basic_value_name = $vv;
                                $value_model -> status = 1;
                                $value_model -> ctime = $now;
                                if( !$value_model -> save() ){
                                    throw new \Exception('属性写入失败');
                                }
                            }
                        }
                    }
                }

                DB::commit();

                return $this -> success();
            }catch ( \Exception $e ){

                DB::rollBack();
                $msg = $e -> getMessage();
                return $this -> fail($msg);

            }

        }

        # 获取当前的分类
        $cate_list = $this -> getCategoryLevel( 0 );

        return view('attr.basicadd',[
            'cate_list' => $cate_list
        ]);
    }

    /**
     * 销售属性列表
     */
    public function saleAttrList( Request $request )
    {
        if( $request -> ajax() ){
            $sql = 'SELECT
                *,
                GROUP_CONCAT(
                    CONCAT( sale_attr_name, ":", value_name )
                    ORDER BY
                    t.category_id ASC SEPARATOR "\r\n"
                    ) AS new_value_name
                FROM
                    (
                    SELECT
                        sa.`category_id`,
                        ca.`cate_name`,
                        sa.`sale_id`,
                        sa.`sale_attr_name`,
                        sv.`sale_value_id`,
                        GROUP_CONCAT( sv.`sale_value_name` ) AS value_name
                    FROM
                        `shop_sale_attr` AS sa
                        LEFT JOIN `shop_sale_value` AS sv ON sa.`sale_id` = sv.`sale_attr_id`
                        LEFT JOIN `shop_category` AS ca ON ca.cate_id = sa.`category_id`
                    GROUP BY
                        sa.`sale_id`
                    ) t GROUP BY t.category_id';

            $list = DB::select( $sql );

            return [
                'code' => 0,
                'msg' => 'success',
                'data' => $list
            ];
        }
        return view('attr.salelist');
    }

    /**
     * 销售属性添加
     */
    public function saleAttr( Request $request )
    {

        if( $request -> ajax() && $request -> method() == 'POST' ){

            $attr_list = $request ->post('attr')??'';
            if( empty($attr_list) ){
                return $this -> fail('请添加属性和属性值');
            }

            $value_list = $request ->post('value')??'';
            if( empty($value_list) ){
                return $this -> fail('请添加属性和属性值');
            }

            $cate_id = $request -> post('cate_id');
            if( empty($cate_id) ){
                return $this -> fail('请选择分类');
            }

            try{

                DB::beginTransaction();
                $now = time();

                # 先写入销售属性
                foreach( $attr_list as $k => $v ){
                    $sale_model = new SaleAttrModel();

                    $sale_model -> category_id = $cate_id;
                    $sale_model -> sale_attr_name = $v;
                    $sale_model -> status = 1;
                    $sale_model -> ctime = $now;
                    if( $sale_model -> save() ){
                        $sale_id = $sale_model -> sale_id;
                    }else{
                        throw new \Exception('属性写入失败');
                    }

                    # 属性下必须有属性值
                    if( empty( $value_list[$k] ) )
                    {
                        throw new \Exception('属性：'. $v .'没有添加属性值' );
                    }

                    # 写入该属性对应的属性值
                    foreach(  $value_list[$k] as $key => $value ){
                        $value_model = new SaleValueModel();
                        $value_model -> sale_attr_id = $sale_id;
                        $value_model -> sale_value_name = $value;
                        $value_model -> value_image = '';
                        $value_model -> category_id = $cate_id;
                        $value_model -> status = 1;
                        $value_model -> ctime = $now;
                        if( !$value_model -> save() )
                        {
                            throw new \Exception('属性值写入失败');
                        }

                    }
                }

                DB::commit();

                return $this -> success();
            }catch (\Exception $e ){

                DB::rollBack();

                $msg = $e -> getMessage();
                return $this -> fail( $msg );

            }

        }

        # 获取当前的分类
        $cate_list = $this -> getCategoryLevel( 0 );

        return view('attr.saleadd',[
        'cate_list' => $cate_list
        ]);
    }

    /**
     * 获取基本属性
     */
    public function showBasicAttr( Request $request )
    {

        if( !$request -> ajax() || $request -> method() !='POST' ){
            return $this ->fail('非法请求');
        }

        $cate_id =  $request ->post('cate_id')??'';

        if( empty( $cate_id ) )
        {
            $this -> fail('缺少cate_id参数');
        }

//        $cate_id = 6;

        $where = [
            [
                'shop_basic_attr.category_id', '=', $cate_id
            ]
        ];

        $list = BasicAttrModel::where( $where )
            -> select('shop_basic_attr.*','bv.basic_value_id','bv.basic_value_name')
            -> leftJoin('shop_basic_value as bv','bv.basic_attr_id','shop_basic_attr.basic_attr_id')
            -> get()
            -> toArray();

        $new = [];

        foreach( $list as $k => $v ){
            if( $v['basic_level'] == 1 ){
                if( empty($new[$v['basic_attr_id']]) ){
                    $new[$v['basic_attr_id']] = $v;
                }else{
                    $new[$v['basic_attr_id']] = array_merge($new[$v['basic_attr_id']] ,$v);
                }
            }else{
                if( $v['basic_value_name'] != '' ){
                    if( empty( $new[$v['basic_attr_pid']]['son'][$v['basic_attr_id']] ) ) {
                        $new[$v['basic_attr_pid']]['son'][$v['basic_attr_id']] = $v;
                        $new[$v['basic_attr_pid']]['son'][$v['basic_attr_id']]['son'][] = $v;
                    }else{
                        $new[$v['basic_attr_pid']]['son'][$v['basic_attr_id']]['son'][] = $v;
                    }
                }else{
                    $new[$v['basic_attr_pid']]['son'][] = $v;
                }
            }
        }

//        echo '<pre/>';
//        print_r( $new );
//        exit;
        return view( 'attr.basicshow',[
            'attr_list'=> $new
        ]);
    }


    /**
     * 获取销售属性
     */
    public function showSaleAttr( Request $request )
    {

        if( !$request -> ajax() || $request -> method() !='POST' ){
            return $this ->fail('非法请求');
        }

        $cate_id =  $request ->post('cate_id')??'';
        if( empty( $cate_id ) )
        {
            $this -> fail('缺少cate_id参数');
        }

//        $cate_id = 6;

        # 取出销售属性和对应的属性值
        $sale_where = [
            [
                'shop_sale_attr.status' , '=' , 1
            ],
            [ 'shop_sale_attr.category_id' , '=' ,$cate_id  ]
        ];

        $list = SaleAttrModel::with('saleAttrValue') -> where($sale_where) -> get() -> toArray();

//        dd($list);exit;

        return view( 'attr.saleshow',[
            'sale_attr' => $list
        ]);
    }


}











