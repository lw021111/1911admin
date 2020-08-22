@include('public/header')
@include('public/left')
<table id="demo" lay-filter="test"></table>

<script src="/layui/layui.js"></script>
<script>
    layui.use('table', function(){
        var table = layui.table;

        //第一个实例
        table.render({
            elem: '#demo'
            ,height: 312
            ,url: '{{url('Attr/saleAttrList')}}' //数据接口
            ,page: true //开启分页
            ,cols: [[ //表头
                {field: 'category_id', title: 'ID', width:80, sort: true, fixed: 'left'}
                ,{field: 'cate_name', title: '分类名称'}
                ,{field: 'new_value_name', title: '属性+属性值'}
            ]]
        });

    });
</script>
@include('public/footer')
