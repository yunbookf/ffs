<?php
/*session*/
session_save_path('./session/');
session_start();
if(empty($_SESSION['login'])){
	header('Location:login.php');
}else{
	define('ADMIN',true);
}
/*载入核心*/
include 'glob.php';
/*参数处理*/
if(empty($_GET)){
	define('MOD','admin');define('ACT','index');
}else{
	define('MOD',$_GET['mode']);define('ACT',$_GET['action']);define('MOP',ROT."app/{$_GET['mode']}/");
}
/*处理系统请求*/
if(MOD=='admin'){
include_once(ROT.'glob/admin/Engine.php');
}else{
/*导航*/
	if(file_exists(MOP)){
		include( MOP.'Admin.php');
	}else{
		ERROR('出错啦！','无效的访问请求，目标应用扩展不存在。');
	}
}
/*页面输出判断*/
if(!empty($FFS['html'])){
	/*读取后台列表*/
	$list = glob(ROT.'app/*/admin.nav');
	$admin_list = '<li><a href="?mode=admin&action=index" >后台首页</a></li><li><a href="?mode=admin&action=siteSet" >全站设置</a></li><li><a href="?mode=admin&action=fileList" >文件管理</a></li><li><a href="?mode=admin&action=reportList" >举报管理</a></li><li><a href="?mode=admin&action=mailConfig" >邮箱设置</a></li><li><a href="?mode=admin&action=adminSet" >管理员设置</a></li>';
	foreach($list as $key){
		$admin = explode('|',file_get_contents($key));
		$nav   = '<li><a href="?mode='.$admin[0].'&action=index" >'.$admin[1].'</a></li>';
		$admin_list = $admin_list.$nav;
	}
	$FFS['html']['tag']['{admin:list}'] = $admin_list;
    $FFS['html']['tag']['{html:url}']  = URL;
	$FFS['html']['tag']['{version1}']  = VER1;
	$FFS['html']['tag']['{version2}']  = VER2;
    HTML_LOAD(ROT.'glob/admin/index.html',$FFS['html']['path']);
    HTML_CONTENT($FFS['html']['tag']);
    HTML_PUT();
}
?>