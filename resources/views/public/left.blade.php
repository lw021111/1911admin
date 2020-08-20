
<div class="layui-side layui-bg-black">
    <div class="layui-side-scroll">
        <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
        <ul class="layui-nav layui-nav-tree"  lay-filter="test">
            <li class="layui-nav-item">
                <a class="" href="/admin/add">管理员管理</a>

            </li>
            <li class="layui-nav-item">
                <a class="" >分类管理</a>
                <dl class="layui-nav-child">
                    <dd><a href="/category/add">添加分类</a></dd>
                    <dd><a href="/category/index">展示分类</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item">
                <a class="" >权限节点管理</a>
                <dl class="layui-nav-child">
                    <dd><a href="/powerNode/add">权限节点添加</a></dd>
                    <dd><a href="/powerNode/list">权限节点列表</a></dd>
                </dl>
            </li>
        </ul>
    </div>
</div>
<div class="layui-body">
