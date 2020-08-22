
<div class="all">
    @foreach( $sale_attr as $k => $v )
        <div class="one">
            <div class="parent">
                <input type="checkbox" name="sale_attr[{{$v['sale_id']}}]" lay-filter="parent"
                       value="{{$v['sale_id']}}|{{$v['sale_attr_name']}}"
                       lay-skin="primary" title="{{$v['sale_attr_name']}}">
            </div>
            <div class="son">
                @foreach( $v['sale_attr_value'] as $kk => $vv )
                    <input type="checkbox" name="sale_value[{{$v['sale_id']}}][]" lay-filter="son"
                           value="{{$v['sale_id']}}|{{$vv['sale_value_id']}}|{{$vv['sale_value_name']}}"
                           lay-skin="primary" title="{{$vv['sale_value_name']}}">
                @endforeach
            </div>
            <hr/>
        </div>
    @endforeach
</div>
<div class="sku">
    <span>组合出来的sku:</span>
    <div id="sku">
        <table class="layui-table" border="1" width="100%" cellpadding="0" cellspacing="0">
            <thead>
            <th>商品名称</th>
            <th>颜色</th>
            <th>版本</th>
            <th>库存</th>
            <th>价格</th>
            <th>操作</th>
            </thead>
        </table>
    </div>
</div>
<script>
    var form, $;
    var goods_name='';
    layui.use(['form', 'upload', 'layedit'], function () {
        form = layui.form;
        $ = layui.jquery;


        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        // 选中属性，属性值跟着联动
        form.on('checkbox(parent)', function (data) {
            if (data.elem.checked == true) {
                $(data.elem).parent().next('.son').find('input').prop('checked', true);
            } else {
                $(data.elem).parent().next('.son').find('input').prop('checked', false);
            }
            form.render();
            showSku();
        });

        // 选中属性值，属性跟着联动
        form.on('checkbox(son)', function (data) {
            if (data.elem.checked == true) {
                $(data.elem).parent().prev('.parent').find('input').prop('checked', true);
            } else {
                let mark = false;
                $(data.elem).parent().find('input').each(function () {
                    if ($(this).prop('checked') == true) {
                        mark = true;
                    }
                })
                if (mark == false) {
                    $(data.elem).parent().prev('.parent').find('input').prop('checked', false);
                }
            }
            form.render();
            showSku();
        });
    });


    function showSku() {
        goods_name = $('[name=goods_name]').val();

        let table_str = '<table  border="1" width="100%" class="layui-table" cellpadding="0" cellspacing="0">';
        let head = showTableHead();
        let body = showTableBody();
        table_str += head;
        table_str += body;
        table_str += '</table>';
        $('#sku').html(table_str);
        form.render();

    }

    // 组装表的主体内容
    function showTableBody(){
        let arr = [];
        let i=0;
        $('.son').each(function(){
            if( $(this).prev('.parent').find('input').prop('checked') ){
                arr[i] = new Array();
            }
            let j=0;
            $(this).find('input:checked').each( function(){
                arr[i][j] = $(this).val();
                j++;
            });
            i++;
        });
        let sku_all = descartes( arr );

        let sku = [];

        let table_body = '';
        for(  var key in sku_all  ){

            let sku_name = goods_name + getSkuName( sku_all[key]  );

            table_body +="<tr>";
            table_body +='<td><input name="sku_name[]" type="hidden" value="'+sku_name+'">'+sku_name+'</td>';
            let sku_attr = '';
            for( var k in sku_all[key] ){
                table_body +='<td>'+ getStrLast(sku_all[key][k])+'</td>';
                sku_attr += getAttrID(sku_all[key][k]) + ',';
            }
            table_body +='<td>' +
                    '<input name="sku_attr_value[]" type="hidden" value="'+sku_attr+'">' +
                    '<input type="text" class="layui-input" name="sku_stock[]"' +
                    ' required size="2" value="10" placeholder="请输入库存"/>' +
                    '</td>';
            table_body +='<td>' +
                    '<input type="text"  name="sku_price[]" class="layui-input" size="2" ' +
                    ' required value="2999" placeholder="请输入价格"/>' +
                    '</td>';
            table_body +='<td><a href="javascript:;" onclick="$(this).parents(\'tr\').remove();">删除</a></td>';
            table_body +="</tr>";
        }
        return table_body;

    }

    // 获取属性值
    function getAttrID( str ){
        let arr = str.split('|');
        let len = arr.length;
        var attr_id = '';
        for(var i in arr ){
            if( i < len -1 ){
                attr_id += arr[i]+'|';
            }
        }
        return attr_id;
    }

    // 获取属性值
    function getStrLast( str ){
        let arr = str.split('|');
        let len = arr.length;
        return arr[len-1];
    }
    // SKU的名字
    function getSkuName( sku_arr ){
        let  sku_name = '';
        for( var i in sku_arr ){
            let arr = sku_arr[i].split('|');
            let len = arr.length;
            sku_name += '-'+arr[len-1];
        }
        return sku_name;
    }

    // 组装表头
    function showTableHead() {
        let table_head = [];
        let i = 0;
        $('.parent').each(function () {
            if ($(this).find('input').prop('checked') == true) {
                table_head[i] = $(this).find('input').attr('title');
                i++;
            }
        });
        let table_head_str = '<thead>';

        table_head_str += '<th>商品名称</th>';
        for (var j in table_head) {
            table_head_str += '<th>' + table_head[j] + '</th>';
        }
        table_head_str += '<th>库存</th>';
        table_head_str += '<th>价格</th>';
        table_head_str += '<th>操作</th>';
        table_head_str += '</thead>';
        return table_head_str;
    }


    // 笛卡尔积算法
    function descartes(list) {
        //parent上一级索引;count指针计数
        var point = {};

        var result = [];
        var pIndex = null;
        var tempCount = 0;
        var temp = [];

        //根据参数列生成指针对象
        for (var index in list) {
            if (typeof list[index] == 'object') {
                point[index] = {'parent': pIndex, 'count': 0}
                pIndex = index;
            }
        }

        //单维度数据结构直接返回
        if (pIndex == null) {
            return list;
        }

        //动态生成笛卡尔积
        while (true) {
            for (var index in list) {
                tempCount = point[index]['count'];
                temp.push(list[index][tempCount]);
            }

            //压入结果数组
            result.push(temp);
            temp = [];

            //检查指针最大值问题
            while (true) {
                if (point[index]['count'] + 1 >= list[index].length) {
                    point[index]['count'] = 0;
                    pIndex = point[index]['parent'];
                    if (pIndex == null) {
                        return result;
                    }

                    //赋值parent进行再次检查
                    index = pIndex;
                }
                else {
                    point[index]['count']++;
                    break;
                }
            }
        }
    }
</script>
<style>
    .sku span{
        margin-left: 20px;
        margin-top: 10px;
        font-size: 22px;
        color: rgba(34, 19, 231, 0.7);
    }
    .all {
        margin-left: 20px;
    }

    .son {
        margin-top: 10px;
        margin-left: 30px;
    }

    table{
        margin-left: 20px;
        width: 97%;
    }
    table thead {
        background-color: #e6e6e6;
        line-height: 40px;
        border: 1px solid rgba(163, 163, 163, 0.23);
    }

    table td {
        line-height: 38px;
        max-width: 15% !important;
        border: 1px solid rgba(133, 133, 133, 0.29);
    }

    td a{
        color: blue;
    }

</style>