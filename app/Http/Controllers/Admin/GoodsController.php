<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CommonController;
use App\models\BasicAttrModel;
use App\models\BasicValueModel;
use App\models\GoodsBasicModel;
use App\models\GoodsModel;
use App\models\GoodsSaleModel;
use App\models\GoodsSkuModel;
use App\models\SaleAttrModel;
use App\models\SaleValueModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPUnit\Util\Exception;

/**
 * 商品管理模块
 * Class GoodsController
 * @package App\Http\Controllers\Admin
 */
class GoodsController extends CommonController
{

    /**
     * 商品列表
     */
    public function goodsList()
    {
        return view('goods.goodslist');
    }

    /**
     * 商品列表
     */
    public function goodsAdd( Request $request )
    {

        /**
         *  shop_goods
            shop_goods_basic_attr
            shop_goods_sale_attr
            shop_goods_sku
         *  1、先写入商品表
         *  2、写入商品的基本属性
         *  3、写入商品的销售属性
         *  4、写入SKU的数据 -> 商品的销售属性
         */
        #
        if( $request -> ajax() && $request -> method() == 'POST' ){

            if( empty( $request ->post('sku_name') ) ){
                return $this -> fail('请先组合SKU');
            }

            # 需要设计到事务
            try{
                # 开启事务
                DB::beginTransaction();

                $now = time();
                $goods_model = new GoodsModel();

                $goods_model -> goods_name = $request -> post('goods_name');
                $goods_model -> goods_title = $request -> post('goods_title');
                $goods_model -> goods_image = $request ->post('goods_img');
                $goods_model -> goods_slider_img = $request ->post('goods_slider_img');
                $goods_model -> cate_id = $request ->post('cate_id');
                $goods_model -> brand_id = $request ->post('brand_id');
                $goods_model -> goods_desc = $request ->post('goods_desc');

                # 1 待审核  2 审核通过  3、上架 4、下架  5、删除
                $goods_model -> status = 1;
                $goods_model -> ctime = $now;

                if( !$goods_model -> save() ) {
                    throw new \Exception('商品表写入失败');
                }

                $goods_id = $goods_model -> goods_id;

                # 写入商品的基本属性 【基本属性会有多条，需要循环入库】
                $basic_list = $request -> post('attr_value');
                foreach( $basic_list as $k => $v ){

                    $basic_cate_id = $k;
                    foreach( $basic_list[$k]  as $kk => $vv ){
                        $basic_model = new GoodsBasicModel();
                        $basic_model -> goods_id = $goods_id;
                        $basic_model -> attr_cate_id = $basic_cate_id;
                        $basic_model -> attr_id = $kk;
                        $basic_model -> value_id = Null;
                        $basic_model -> value_name =  array_pop($vv);
                        $basic_model -> status = 1;
                        $basic_model -> ctime = $now;
                        if( !$basic_model -> save() ){
                            throw new \Exception('写入商品基本属性失败');
                        }
                    }
                }

                # 写入商品的销售属性
                $sale_attr = $request ->post('sku_attr_value');

                #循环写入对应的数据
                foreach( $sale_attr as $sk => $sv ){
                    $str = trim( $sv , ',' );
                    $sale_attr_this = explode( ',' , $str );
                    $this_sale_id = '';
                    $this_attr_value_id = '';
                    foreach( $sale_attr_this as $ak => $av ){
                        $arr = explode( '|' , trim( $av,'|' ) );
                        $this_attr_value_id .= ','.$arr[count($arr)-1];
                        $sale_model = new GoodsSaleModel();
                        $sale_model -> goods_id = $goods_id;
                        $sale_model -> sale_attr_id = array_shift($arr);
                        $sale_model -> sale_value_id = array_shift($arr);
                        $sale_model -> status = 1;
                        $sale_model -> ctime = $now;
                        if( !$sale_model -> save() ){
                            throw new \Exception('销售属性写入失败');
                        }
                        $this_sale_id .= ','.$sale_model -> id;

                    }

                    # 因为SKU要和销售属性关联
                    $sku_name_arr = $request ->post('sku_name');
                    $sku_price_arr = $request -> post('sku_price');

                    $sku_model = new GoodsSkuModel();
                    $sku_model -> goods_id = $goods_id;
                    $sku_model -> sku_name = $sku_name_arr[$sk];
                    $sku_model -> sku_title = $sku_name_arr[$sk];
                    $sku_model -> sku_image = $request ->post('goods_img');
                    $sku_model -> sku_slider_img = $request ->post('goods_slider_img');
                    $sku_model -> sku_price = $sku_price_arr[$sk];
                    $sku_model -> sku_sale_number = 0;
                    $sku_model -> sku_score = $sku_price_arr[$sk];
                    $sku_model -> cate_id = $request ->post('cate_id');
                    $sku_model -> brand_id = $request ->post('brand_id');
                    $sku_model -> sku_attr_id = trim($this_sale_id,',');
                    $sku_model -> sku_value_id = $this_attr_value_id;
                    $sku_model -> status = 1;
                    $sku_model -> ctime = $now;
                    if( ! $sku_model -> save() ){
                        throw new \Exception( 'SKU写入失败');
                    }
                }

                DB::commit();

                return $this -> success('SKU添加成功');

            }catch ( \Exception $e ){
                DB::rollBack();
                $error_msg = $e -> getMessage();

                return $this -> fail($error_msg);
            }

        }else{
            return view('goods.goodsadd',[
                'cate_list' => $this -> getCategoryLevel(0),
                'brand_list' => $this -> getBrandList(),
            ]);
        }

    }

    /**
     * 上传商品的图片
     */
    public function uploadGoodsImg( Request $request )
    {

        # 处理上传的逻辑
        if( $request -> hasFile('file')  && $request->file('file')->isValid() ){

            $photo = $request->file('file');
            $extension = $photo->extension();
            $path = '/goods_imgs/' .date('Ym').'/'.date('d');
            $file_name = uniqid() . '-' . rand(1000,9999) .'.'.$extension;
            $store_result = $photo->storeAs( $path , $file_name );
            $url = str_replace( $request -> route() ->uri() ,  '' , $request -> url());
            $output = [
                'extension' => $extension,
                'store_result' =>  $url .$store_result,
                'path' =>  $store_result,
            ];
            return $this -> success( $output );
        }else{
            return $this -> fail('没有上传文件');
        }
    }

}










