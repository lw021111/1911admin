@include('public/header')
@include('public/left')
<div style="margin-top: 10px"></div>
<table id="demo" lay-filter="test"></table>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-xs" lay-event="detail">查看</a>
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>
<script>
layui.use('table', function(){
    var table = layui.table;

    //第一个实例
    table.render({
        elem: '#demo'
        ,height: 480
        ,url: '{{url('/powerNode/list')}}' //数据接口
        ,page: true //开启分页
        ,cols: [[ //表头
            {field: 'power_node_id', title: 'ID', sort: true, fixed: 'left'}
            ,{field: 'power_node_name', title: '节点的名称'}
            ,{field: 'power_node_level', title: '节点的层级', sort: true}
            ,{field: 'status_desc', title: '状态'}
            ,{field: 'create_date', title: '创建时间', sort: true}
            ,{fixed: 'right',title: '操作',align:'center', toolbar: '#barDemo'}
        ]]
    });

    // 表单的操作
    table.on('tool(test)', function(obj){ //注：tool 是工具条事件名，test 是 table 原始容器的属性 lay-filter="对应的值"
        var data = obj.data; //获得当前行数据
        var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
        var tr = obj.tr; //获得当前行 tr 的 DOM 对象（如果有的话）

        if(layEvent === 'detail'){ //查看
            alert('查看');
            //do somehing
        } else if(layEvent === 'del'){ //删除
            layer.confirm('真的删除行么', function(index){
                obj.del(); //删除对应行（tr）的DOM结构，并更新缓存
                layer.close(index);
                //向服务端发送删除指令
            });
        } else if(layEvent === 'edit'){ //编辑
            //do something

            //同步更新缓存对应的值
            obj.update({
                username: '123'
                ,title: 'xxx'
            });
        } else if(layEvent === 'LAYTABLE_TIPS'){
            layer.alert('Hi，头部工具栏扩展的右侧图标。');
        }
    });
});

</script>
@include('public/footer')
