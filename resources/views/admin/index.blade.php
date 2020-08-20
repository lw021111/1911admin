<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"> 
	<title>CRM-</title>
	<link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css">  
	<script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
	<script src="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<nav class="navbar navbar-default" role="navigation">
	<div class="container-fluid">
	<div class="navbar-header">
		<a class="navbar-brand" href="#">Admin</a>
	</div>
	<div>
		<ul class="nav navbar-nav">
			
			<li class="active"><a href="{{url('admin')}}">管理员管理</a></li>
			<li><a href="{{url('user')}}">用户管理</a></li>
			@if(session('admin'))
			<li><a href="{{url('login/quit')}}">退出</a></li>
			@else
			<li><a href="{{url('login')}}">登陆</a></li>
			@endif
	</div>
	</div>
</nav>
<center><h1>管理员管理</h1></center>
<a href="{{url('admin/create')}}" class="btn btn-primary">添加</a><hr>
<table class="table table-hover">
	
	<thead>
		<tr>
			<th>ID</th>
			<th>管理员名称</th>
			<th>管理员性别</th>
			<th>管理员年龄</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
		
		<tr>
			<td>1</td>
			<td>2</td>
			<td>3</td>
			<td>4</td>
			<td>
				<a href="" class="btn btn-warning">编辑</a> || 
				<a href="" class="btn btn-danger">删除</a>
			</td>
		</tr>
		
		
	</tbody>
</table>
</body>
</html>