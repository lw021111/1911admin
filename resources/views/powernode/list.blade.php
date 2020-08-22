@include('public/header')
@include('public/left')



<table class="layui-table">
    <colgroup>
        <col width="150">
        <col width="200">
        <col>
    </colgroup>
    <thead>
    <tr>
        <th>权限节点的名称</th>
        <th>权限的层级</th>
        <th>权限对应的访问路径</th>
        <th>状态</th>
        <th>添加时间</th>
        <th>父级id</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    @foreach($sql as $v)
        <tr>
            <td>{{$v->power_node_name}}</td>
            <td>{{$v->power_node_level}}</td>
            <td>{{$v->power_node_url}}</td>
            <td>{{$v->status}}</td>
            <td>{{$v->ctime}}</td>
            <td>{{$v->power_node_pid}}</td>
            <th>
                <a href="#" class="layui-btn layui-btn-radius layui-btn-danger">删除</a>
            </th>
        </tr>
    @endforeach
    </tbody>
</table>
<style>
    .pagination-outer{ text-align: center; }
    .pagination{
        font-family: 'Allerta Stencil', sans-serif;
        display: inline-flex;
        position: relative;
    }
    .pagination li a.page-link{
        color: #fff;
        background: transparent;
        font-size: 21px;
        line-height: 35px;
        height: 38px;
        width: 38px;
        padding: 0;
        margin: 0 8px;
        border: none;
        position: relative;
        z-index: 1;
        transition: all 0.4s ease 0s;
    }
    .pagination li.active a.page-link,
    .pagination li a.page-link:hover,
    .pagination li.active a.page-link:hover{
        color: #fff;
        background-color: transparent;
    }
    .pagination li a.page-link:before,
    .pagination li a.page-link:after{
        content:'';
        background: linear-gradient(225deg,#f857a6,#ff5858);
        height: 100%;
        width: 100%;
        border: 3px solid #fff;
        box-shadow: 0 0 3px #000;
        border-radius: 50%;
        opacity: 1;
        transform: translateX(-50%) translateY(-50%) rotate(-45deg);
        position: absolute;
        top: 50%;
        left: 50%;
        z-index: -1;
        transition: all 0.3s ease-in;
    }
    .pagination li a.page-link:hover:before,
    .pagination li.active a.page-link:before{
        border-radius: 50% 0 50% 50%;
    }
    .pagination li a.page-link:after{
        background: #fff;
        height: 5px;
        width: 5px;
        border: none;
        box-shadow: 0 0 0 transparent;
        opacity: 0;
        transform: translateX(-50%) translateY(0) rotate(0);
        top: auto;
        bottom: 0;
    }
    .pagination li a.page-link:hover:after,
    .pagination li.active a.page-link:after{
        opacity: 1;
        bottom: 85%;
    }
    .pagination li:first-child a.page-link:before{
        transform: translateX(-50%) translateY(-50%) rotate(-135deg);
    }
    .pagination li:first-child a.page-link:hover:before{ border-radius: 50% 0 50% 50%; }
    .pagination li:first-child a.page-link:after{
        transform: translateX(0) translateY(-50%);
        top: 50%;
        bottom:auto;
        left: auto;
        right: 0;
    }
    .pagination li:first-child a.page-link:hover:after{ right: 80%; }
    .pagination li:last-child a.page-link:before{
        transform: translateX(-50%) translateY(-50%) rotate(45deg);
    }
    .pagination li:last-child a.page-link:hover:before{ border-radius: 50% 0 50% 50%; }
    .pagination li:last-child a.page-link:after{
        transform: translateX(0) translateY(-50%);
        bottom:auto;
        top: 50%;
        left: 0;
    }
    .pagination li:last-child a.page-link:hover:after{ left: 80%; }
    @media only screen and (max-width: 480px){
        .pagination{ display: block; }

        .pagination li{
            margin-bottom: 10px;
            display: inline-block;
        }
    }
</style>
</head>
<body>

<div style="height:140px;"></div>

<nav class="pagination-outer" aria-label="Page navigation">
    {{$sql->links()}}
</nav>
</div>


{{$sql->links()}}

@include('public/footer')
