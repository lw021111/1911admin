<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>RBAC登陆</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/layui/css/layui.css"  media="all">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="/layui/layui.js" charset="utf-8"></script>
    <!-- 注意：如果你直接复制所有代码到本地，上述css路径需要改成你本地的 -->
</head>
<body  style="background-color: rgba(12, 12, 12, 0.76)">

<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;padding-left: 100px">
    <legend>RBAC系统管理</legend>
</fieldset>
<div class="login layui-form">
    <div class="layui-form-item">
        <div style="font-size:27px;font-weight:normal;margin-top: -30px">登陆</div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">管理员账号</label>
        <div class="layui-input-inline">
            <input type="text" name="admin_name" required
                   placeholder="请输入管理员账号" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">管理员密码</label>
        <div class="layui-input-inline">
            <input type="text" name="password" required
                   placeholder="请输入管理员密码" autocomplete="off" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <button class="layui-btn" lay-submit lay-filter="formDemo">登陆</button>
        <button type="reset" class="layui-btn layui-btn-primary">取消</button>
    </div>
</div>

<style>
    body{
        color: white;
        font-weight: bold;
    }
    .login{
        border-radius: 20px;
        text-align: center;
        background-color: white;
        display:inline-block;
        color: #000;
        margin-top: 5%;
        margin-left:36%;
        padding:90px 80px;
    }
</style>
<script>
    var $;
    layui.use([ 'form' , 'layer' ], function() {
        var form = layui.form;
        $ = layui.jquery;
        var layer = layui.layer;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //监听提交
        form.on('submit(formDemo)', function(data){
            if( data.field.admin_name == '' ){
                layer.msg('请输入管理员账号');
                return false;
            }
            if( data.field.password == '' ){
                layer.msg('请输入管理员的密码');
                return false;
            }

//            layer.msg(JSON.stringify(data.field));
//            return false;
            $.ajax({
                url:'{{url('/login')}}',
                data:data.field,
                type:'post',
                dataType:'json',
                success:function( json_info ){
                    if( json_info.status == 200 ){
                        window.location.href = "{{url('/')}}";
                    }else{
                        alert(json_info.msg);
                    }
                }
            })
            return false;
        });
    });
</script>
</body>
</html>