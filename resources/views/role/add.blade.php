@include('public/header')
@include('public/left')
<div style="margin-top: 10px"></div>
<form class="layui-form" action="{{url('/role/add')}}" method="post">
    <div class="layui-form-item">
        <label class="layui-form-label">角色的名称</label>
        <div class="layui-input-inline">
            <input type="text" name="role_name" required  lay-verify="required" placeholder="请输入标题" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">是否启用</label>
        <div class="layui-input-block">
            <input type="radio" name="status" value="1" title="启用" checked>
            <input type="radio" name="status" value="0" title="不启用" >
        </div>
    </div>
    <hr/>
    <div class="layui-form-item">
        <label class="layui-form-label">权限操作</label>
        <div class="layui-input-block">
            @foreach( $all_node as $k => $v )
                <div class="parent">
                    <input type="checkbox" name="like[]" lay-filter="parent"  value="{{$v['power_node_id']}}"
                           lay-skin="primary" title="{{$v['power_node_name']}}"><br/>
                    <div class="son">
                        @if( isset( $v['son']) )
                        @foreach( $v['son'] as $kk => $vv )
                            <input type="checkbox" name="like[]" lay-filter="son" value="{{$vv['power_node_id']}}"
                                   lay-skin="primary" title="{{$vv['power_node_name']}}">
                        @endforeach
                        @endif
                    </div>
                    <hr/>
                </div>
            @endforeach
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn"  type="submit">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>
<style>
    .son{
        margin-left: 40px;
        margin-top: 10px;
    }
</style>
<script>

    //Demo
    layui.use('form', function(){
        var form = layui.form;
        $ = layui.jquery;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

         父选子
        form.on('checkbox(parent)', function(data){
            if(  data.elem.checked == true  ){
                data.othis.parent('.parent').find('.son input').prop('checked', true);
            }else{
                data.othis.parent('.parent').find('.son input').prop('checked', false);
            }
            form.render();

        });

         子选父
        form.on('checkbox(son)', function(data){
            if(  data.elem.checked == true  ){
                data.othis.parents('.parent').find('input').first().prop('checked', true);
            }else{
                // 先判断当前的子级有没有选中的，如果有选中的，不修改父级，如果全部都是不选中的，把父级也修改为不选中
                let mark = false;
                data.othis.parent('.son').find('input').each(function () {
                    console.log($(this).prop('checked'));
                    if( $(this).prop('checked') == true ){
                        mark = true;
                    }
                })
                if( mark == false )
                {
                    data.othis.parents('.parent').find('input').first().prop('checked', false);
                }
            }
            form.render();
        });

        //监听提交
        {{--form.on('submit(formDemo)', function(data){--}}
            {{--layer.msg(JSON.stringify(data.field));--}}
{{--//            return false;--}}
            {{--$.ajax({--}}
                {{--url:'{{url('/role/add')}}',--}}
                {{--data:data.field,--}}
                {{--type:'post',--}}
                {{--dataType:'json',--}}
                {{--success:function( json_info ){--}}
                    {{--if( json_info.status == 200 ){--}}
                        {{--alert('success');--}}
                    {{--}else{--}}
                        {{--alert('fail');--}}
                    {{--}--}}
                {{--}--}}
            {{--})--}}

//            return false;

    });
</script>
@include('public/footer')
