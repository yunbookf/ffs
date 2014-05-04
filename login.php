<?php
session_save_path('./session/');
session_start();
if(!empty($_POST['password'])){
	include('glob.php');
	include('glob/admin/config.php');
	if(STR_ENCRYPT($_POST['password'])==ADMIN_PASSWORD){
		$_SESSION['login'] = true;
		header('Location:admin.php');
	}else{
		echo '<script type="text/JavaScript">alert("登陆密码错误");</script>';
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>FFS-后台</title>
<link href="glob/admin/style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.loginbox{ width:300px; height:120px; position:absolute; left:50%; top:50%; margin-left:-170px; margin-top:-130px; padding:20px;}
.loginbox p{ line-height:30px; font-size:14px; margin-bottom:10px;}
.loginbox input{ width:200px;}
.loginbox input.roundbtn{ margin-top:10px; width:50px;}
</style>
</head>

<body>
<div id="container">
  <form action="?action=login" method="post" class="loginbox roundbox" name="loginbox">
    <p>
      <label for="username">用户名：</label>
      <input type="text" name="username" value="admin " />
    </p>
    <p>
      <label for="password">密&nbsp;&nbsp;&nbsp;码：</label>
      <input type="password" name="password" />
    </p>
    <p>
      <input type="submit" value="登录" name="loginsubmit" class="roundbtn" />
    </p>
  </form>
</div>
</body>
</html>
