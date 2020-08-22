@include('public/header')
@include('public/left')
<div style="margin-top: 10px"></div>
<form class="layui-form" action="">
    <div class="layui-form-item">
        <label class="layui-form-label">管理员账号</label>
        <div class="layui-input-inline">
            <input type="text" name="admin_name" required  lay-verify="required" value="zhangsan"
                   placeholder="请输入管理员名称" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">真实名字</label>
        <div class="layui-input-inline">
            <input type="text" name="real_name" required  lay-verify="required" value="张三"
                   placeholder="请输入管理员真实姓名" autocomplete="off" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">密码</label>
        <div class="layui-input-inline">
            <input type="text" name="pwd" required  lay-verify="required"  value="123456"
                   placeholder="请输入你的密码" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">确认密码</label>
        <div class="layui-input-inline">
            <input type="text" name="repwd" required  lay-verify="required" value="123456"
                   placeholder="请再次输入密码" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">手机号</label>
        <div class="layui-input-inline">
            <input type="text" name="phone" required  lay-verify="required" value="13233334444"
                   placeholder="请输入手机号" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">邮箱</label>
        <div class="layui-input-inline">
            <input type="text" name="email" required  lay-verify="required" value="112306@qq.com"
                   placeholder="请输入标题" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">管理员类型</label>
        <div class="layui-input-block">
            <input type="radio" lay-filter="admin_type" name="admin_type" value="2" title="普通管理员" checked>
            <input type="radio" lay-filter="admin_type" name="admin_type" value="1" title="超级管理员" >
        </div>
    </div>
    <div class="layui-form-item" id="all_role">
        <label class="layui-form-label">现有角色</label>
        <div class="layui-input-block">
            <div class="role">
                @foreach( $role_list as $k => $v )
                    <div class="role_name">
                        <input type="checkbox" name="role[]" lay-filter="parent"  value="{{$v['role_id']}}"
                               lay-skin="primary" title="{{$v['role_name']}}">
                    </div>
                    <hr/>
                    <div class="role_parent"><span class="title">权限：</span>
                        <span style="color:rgba(47, 47, 47, 0.6)">{{$v['power_node_name']}}</span>
                    @foreach( $v['power_list'] as $kk => $vv )
                        <span style="color:rgba(47, 47, 47, 0.6)">{{$vv['power_node_name']}}</span>
                    @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit lay-filter="formDemo">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>
<style>
    .role{

    }
    .role_name{
        color: #0E0EFF;
        margin-top: 10px;
    }
    .title{
        color:#00b0e8;
    }
</style>
<script>
    //Demo
    var $;
    layui.use([ 'form' , 'layer' ], function(){
        var form = layui.form;
        $ = layui.jquery;
        var layer = layui.layer;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        form.on('radio(admin_type)', function(data){
            if( data.value == 1 ) {
                layer.msg('超级管理员不受后台权限控制，请慎重操作');
                $('#all_role').hide();
            }else{
                $('#all_role').show();
            }
        });
        //监听提交
        form.on('submit(formDemo)', function(data){
            layer.msg(JSON.stringify(data.field));
            $.ajax({
                url:'{{url('/admin/add')}}',
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
