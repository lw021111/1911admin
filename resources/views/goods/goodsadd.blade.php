@include('public/header')
@include('public/left')

<form class="layui-form" >
<div style="margin-left: 10px" >

    <div class="layui-tab layui-tab-brief"  lay-filter="docDemoTabBrief">
        <ul class="layui-tab-title">
            <li class="layui-this">商品的基本信息</li>
            <li>商品的基本属性</li>
            <li>商品的销售属性</li>
        </ul>
    </div>
    <div class="tab">
        <div class="tab_son tabshow">
            <div class="layui-form-item">
                <label class="layui-form-label">商品名称</label>
                <div class="layui-input-inline">
                    <input type="text" name="goods_name" required  lay-verify="required" placeholder="请输入商品名称" autocomplete="off" class="layui-input">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">商品副标题</label>
                <div class="layui-input-inline">
                    <input type="text" name="goods_title" required  lay-verify="required" placeholder="请输入商品标题" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">品牌</label>
                <div class="layui-input-inline">
                    <select name="brand_id" lay-verify="required">
                        <option value="">请选择</option>
                        @foreach( $brand_list as $k => $v  )
                            <option value="{{$v->brand_id}}">{{$v->brand_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">分类</label>
                <div class="layui-input-inline">
                    <select name="cate_id" lay-filter="cate" lay-verify="required">
                        <option value="">请选择</option>
                        @foreach( $cate_list as $k => $v )
                            <option value="{{$v['cate_id']}}"
                                    @if($v['level'] !=3 ) disabled @endif
                            >{{str_repeat(" |- ",$v['level'] -1 )}}{{$v['cate_name']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">商品图片</label>
                <div class="layui-input-inline">
                    <button type="button" class="layui-btn" id="test1">
                        <i class="layui-icon">&#xe67c;</i>上传图片
                    </button>
                    <img id="goods_img" style="display: none" src=""/>
                    <input type="hidden" readonly name="goods_img" />
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">商品轮播图</label>
                <div class="layui-input-block">
                    <div class="layui-upload">
                        <button type="button" class="layui-btn" id="test2">多图片上传</button>
                        <blockquote class="layui-elem-quote layui-quote-nm" style="margin-top: 10px;">
                            预览图：
                            <div class="layui-upload-list" id="demo2"></div>
                        </blockquote>
                    </div>
                    <input type="text" id="slider" name="goods_slider_img"/>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">商品介绍</label>
                <div class="layui-input-block">
                    <textarea id="demo" name="goods_desc" style="display: none;"></textarea>
                </div>
            </div>
        </div>
        <div class="tab_son">
            <div style="color: red">请先选择分类</div>
        </div>
        <div class="tab_son">
            <div style="color: red">请先选择分类</div>
        </div>
    </div>

</div>

<div class="layui-form-item">
    <div class="layui-input-block">
        <button class="layui-btn" lay-submit lay-filter="formDemo">立即添加</button>
        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
    </div>
</div>
</form>
<script>
    var form,$;
    layui.use(['form','upload','layedit'], function(){
        form = layui.form;
        $= layui.jquery;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // 根据分类id吧分类相关的数据取出来 【销售属性和基本属性】
        form.on('select(cate)', function(data){
//            console.log(data.elem); //得到select原始DOM对象
//            console.log(data.value); //得到被选中的值
//            console.log(data.othis); //得到美化后的DOM对象
            $.ajax({
                url:'{{url('/showBasicAttr')}}',
                data:'cate_id='+data.value,
                type:'post',
                dataType:'html',
                success:function( html_info ){
                    console.log($('.tab_son').eq(1).html());
                    $('.tab_son').eq(1).html(html_info);
                    form.render();
                }
            })
            $.ajax({
                url:'{{url('/showSaleAttr')}}',
                data:'cate_id='+data.value,
                type:'post',
                dataType:'html',
                success:function( html_info ){
                    console.log($('.tab_son').eq(2).html());
                    $('.tab_son').eq(2).html(html_info);
                    form.render();
                }
            })
        });


        $('.layui-tab-title li').click( function(){
            let index = $(this).index();
            $('.tab').find('.tab_son').removeClass( 'tabshow' );
            $('.tab').find('.tab_son').eq( index).addClass('tabshow');
        })

        layui.use('layedit', function(){
            var layedit = layui.layedit;
            layedit.build('demo'); //建立编辑器
        });
        var upload = layui.upload;

        //执行实例
        // 商品的图片上传
        var uploadInst = upload.render({
            elem: '#test1' //绑定元素
            ,url: '{{url('uploadGoodsImg')}}' //上传接口

            ,done: function(res){
                //上传完毕回调
                console.log( res);
                $('#goods_img').prop('src' , res.data.store_result);
                $('#goods_img').show();
                $('[name=goods_img]').val(res.data.path);
            }
            ,error: function(){
                //请求异常回调
            }
        });

        //多图片上传
        // 商品的轮播图的上传
        upload.render({
            elem: '#test2'
            ,url: '{{url('uploadGoodsImg')}}' //改成您自己的上传接口
            ,multiple: true
            ,before: function(obj){
                //预读本地文件示例，不支持ie8
                obj.preview(function(index, file, result){
                    $('#demo2').append('<img class="img" src="'+ result +'" alt="'+ file.name +'" class="layui-upload-img">')
                });
            }
            ,done: function(res){
                //上传完毕
                console.log( res );
                if( res.status == 200 ){
                    let origin_path = $('#slider').val( );
                    origin_path += '|' + res.data.path;
                    $('#slider').val( origin_path )
                }
            }
        });

        //监听提交
        form.on('submit(formDemo)', function(data){
            console.log(JSON.stringify(data.field));
            $.ajax({
                url:'{{url('goods/goodsAdd')}}',
                data:data.field,
                type:'post',
                dataType:'json',
                success:function( json_info ){
                    layer.msg(JSON.stringify(json_info));
                }
            })

            return false;
        });
    });


</script>
<style>
    #goods_img{
        width: 100px;
        height: 100px;
    }
    .img{
        width: 100px;
        height: 100px;
    }
    .tab_son{
        display: none;
    }
    .tabshow{
        display: block!important;
    }
</style>
@include('public/footer')
