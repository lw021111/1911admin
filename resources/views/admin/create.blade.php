<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"> 
	<title> 管理员添加 </title>
	<link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css">  
	<script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
	<script src="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<center><h2>管理员管理</h2></center>

<form class="form-horizontal" role="form" method="post" action="store" enctype="multipart/form-data">
@csrf
	
	<div class="form-group">
		<label for="lastname" class="col-sm-2 control-label">管理员名称</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" name="admin_name" id="lastname" 
				   placeholder="请输入管理员名称">
		</div>
	</div>
	<div class="form-group">
	<label for="firstname" class="col-sm-2 control-label">管理员性别</label>
	    <div>
	        <input type="radio" name="admin_sex" id="optionsRadios1" value="1" checked> 男
	        <input type="radio" name="admin_sex" id="optionsRadios1" value="2"> 女
	    </div>
	</div>
	<div class="form-group">
		<label for="lastname" class="col-sm-2 control-label">管理员年龄</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" name="admin_age" id="lastname" 
				   placeholder="请输入管理员年龄">
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-default">添加</button>
		</div>
	</div>
</form>

</body>
</html>