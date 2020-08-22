
@foreach( $attr_list as $k => $v )
    <div class="basicone">
        <div class="cate">
            <div>{{$v['basic_name']}}</div>
        </div>
        <div class="attr">
            @foreach($v['son'] as $kk => $vv )
                <div>{{$vv['basic_name']}}</div>
                @if( !empty($vv['son']) )
                    <select name="attr_value[{{$vv['basic_attr_pid']}}][{{$vv['basic_attr_id']}}][]">
                        <option>请选择{{$vv['basic_name']}}</option>
                        @foreach($vv['son'] as $kkk => $vvv )
                            <option value="{{$vvv['basic_value_name']}}">{{$vvv['basic_value_name']}}</option>
                        @endforeach
                    </select>
                @else
                    <input type="text"  class="layui-input"
                           name="attr_value[{{$vv['basic_attr_pid']}}][{{$vv['basic_attr_id']}}][]" placeholder="请输入{{$vv['basic_name']}}" />
                @endif
            @endforeach
        </div>
    </div>
    <hr/>
@endforeach

<style>
    .basicone{
        width: 80%;
        margin-left: 10px;
        /*display: -webkit-flex;*/
        /*align-items:center;*/
        /*text-align: center;*/
        /*display: -webkit-flex;*/
    }
    .cate{
        float: left;
        width: 20%;
    }
    .attr{
        float: left;
    }
</style>