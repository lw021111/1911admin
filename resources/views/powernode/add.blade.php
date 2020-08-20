@include('public/header')
@include('public/left')
<div style="margin-top: 10px"></div>
<form class="layui-form" action="">
    <div class="layui-form-item">
        <label class="layui-form-label">节点的名称</label>
        <div class="layui-input-inline">
            <input type="text" name="node_name" required  lay-verify="required" placeholder="请输入标题" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">节点路径</label>
        <div class="layui-input-inline">
            <input type="text" name="path" required  lay-verify="required" placeholder="请输入标题" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">父级节点</label>
        <div class="layui-input-inline">
            <select name="pid" lay-verify="required">
                <option value="0">请选择</option>
                @foreach( $power_list as $k => $v )
                    <option value="{{$v->power_node_id}}">{{$v->power_node_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">是否启用</label>
        <div class="layui-input-block">
            <input type="radio" name="status" value="1" title="启用" checked>
            <input type="radio" name="status" value="0" title="不启用" >
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>
<script>

    //Demo
    var $;
    layui.use('form', function(){
        var form = layui.form;
        $ = layui.jquery;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //监听提交
        form.on('submit(formDemo)', function(data){
            layer.msg(JSON.stringify(data.field));

            $.ajax({
                url:'{{url('/powerNode/add')}}',
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
</script>
@include('public/footer')
