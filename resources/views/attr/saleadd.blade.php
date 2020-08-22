@include('public/header')
@include('public/left')
<form class="layui-form" action="">
    <div class="layui-form-item">
        <label class="layui-form-label">请选择分类</label>
        <div class="layui-input-inline">
            <select name="cate_id"  lay-verify="required">
                <option value="">请选择分类</option>
                @foreach( $cate_list as $k => $v )
                    <option value="{{$v['cate_id']}}"
                            @if($v['level'] !=3 ) disabled @endif
                    >{{str_repeat(" |- ",$v['level'] -1 )}}{{$v['cate_name']}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="layui-form-item" id="addAttr">
        <label class="layui-form-label">添加属性</label>
        <div class="layui-input-block">
            <button type="button" onclick="addAttr(1,$(this))" class="layui-btn">
                <i class="layui-icon">&#xe608;</i>添加销售属性
            </button>
        </div>
    </div>

    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-danger">重置</button>
        </div>
    </div>
</form>

<script>
    //Demo
    var form,$;
    layui.use('form', function(){
        form = layui.form;
        $ = layui.jquery;


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //监听提交
        form.on('submit(formDemo)', function(data){
            layer.msg(JSON.stringify(data.field));
            console.log( data.field );
            $.ajax({
                url:'{{url('/Attr/saleAttr')}}',
                data:data.field,
                type:'post',
                dataType:'json',
                success:function( json_info ){
                    if( json_info.status == 200 ){
                        alert('success');
                    }else{
                        alert('fail');
                    }
                }
            })
            return false;
        });

    });

    function addAttr( type , ele ){
        if( type == 1 ){
            let number = $('[name^=attr]').last().attr('number');
            if( number == undefined )
            {
                number = 0;
            }else{
                number = parseInt( number );
                number ++;
            }

            let one = '<div class="layui-form-item attr">' +
                    '<label class="layui-form-label">销售属性</label>' +
                    '<div class="layui-input-inline">' +
                    '<input type="text" name="attr['+number+']" number="'+number+'" required  lay-verify="required"' +
                    ' placeholder="请输入销售属性" autocomplete="off" class="layui-input">' +
                    '</div><button type="button" onclick="addAttr(2,$(this))" class="layui-btn">' +
                    '<i class="layui-icon">&#xe608;</i>添加属性值' +
                    '</button><button type="button" onclick="delAttr($(this))"  class="layui-btn  layui-btn-danger">' +
                    '<i class="layui-icon">&#xe640;</i>' +
                    '</button><hr/></div>';

            $('#addAttr').parent().find('.layui-form-item').last().prev().after(one);
        }else if( type == 2 ){

            // 获取该属性对应的分类的number
            let number = ele.parents('.attr').find('input').attr('number');

            let attr_number = ele.parents('.layui-form-item').find('.value').last().find('input').attr('number');
            if( attr_number == undefined )
            {
                attr_number = 0;
            }else{
                attr_number = parseInt( attr_number );
                attr_number ++;
            }

            let two = '<div class="layui-form-item value">' +
                    '<label class="layui-form-label">属性值</label>' +
                    '<div class="layui-input-inline">' +
                    '<input type="text" name="value['+number+'][]" number="'+attr_number+'" required  lay-verify="required"' +
                    ' placeholder="请输入属性值的名字" autocomplete="off" class="layui-input">' +
                    '</div><button onclick="delAttr($(this))" type="button" class="layui-btn  layui-btn-danger">' +
                    '<i class="layui-icon">&#xe640;</i>' +
                    '</button></div>';
            ele.parent().append( two );
        }
    }
    function delAttr( ele ){
        ele.parent('.layui-form-item').remove( );
    }
</script>
<style>
    .attr{
        margin-top: 10px;
        margin-left: 20px;
    }
    .value{
        margin-top: 10px;
        margin-left: 40px;
    }
</style>
@include('public/footer')
