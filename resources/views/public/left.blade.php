
<div class="layui-side layui-bg-black">
    <div class="layui-side-scroll">
        <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
        <ul class="layui-nav layui-nav-tree"  lay-filter="test">
            @foreach( $node_list as $k => $v )
                @if(  strstr($route,$v['power_node_url']  ) !== false )
                    <li class="layui-nav-item layui-nav-itemed">
                @else
                    <li class="layui-nav-item">
                @endif
                    <a class="" href="javascript:;">{{$v['power_node_name']}}</a>
                    <dl class="layui-nav-child">
                        @if( isset($v['son']) )
                        @foreach( $v['son'] as $kk => $vv )
                            <dd><a href="{{url($vv['power_node_url'])}}">{{$vv['power_node_name']}}</a></dd>
                        @endforeach
                        @endif
                    </dl>
                </li>
            @endforeach

        </ul>
    </div>
</div>

<div class="layui-body" style="margin-top: 15px">