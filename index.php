<?php

if(!is_file('install/install.lock')) {
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Last-Modified: '.gmdate("D, d M Y H:i:s").' GMT');
	header('Cache-Control: no-cache, must-revalidate');
	header('Pramga: no-cache'); 
	header('Location: install/');
} else {
	/*载入核心*/
	include 'glob.php';
	/*站点是否关闭*/
	SITE_CLOSE();
	/*导航*/
	if(empty($_GET)&&empty($_SERVER['QUERY_STRING'])){
		if(stripos($_SERVER['SCRIPT_NAME'],'index.php')!==false){
			define('MOD','index');define('VAL','index');define('MOP',ROT.'app/index/');
		}else{
			define('MOD','admin');define('VAL','index');define('MOP',ROT.'app/admin/');
		}
	}
	if(stripos($_SERVER['QUERY_STRING'],'/')!==false) {
		$url_query = substr($_SERVER['QUERY_STRING'],1);
		$url_query = explode('/',$url_query);
		define('MOD',$url_query[0]);define('VAL',$url_query[1]);define('MOP',ROT."app/{$url_query[0]}/");	
	}
	if(file_exists(MOP)){
		include( MOP.'Engine.php');
	}else{
		ERROR('出错啦！','无效的访问请求，目标应用扩展不存在。');
	}
	/*举报*/
	if(!empty($_POST['reportBtn'])) {
		$result=FILE_REPORT(trim($_POST['id']),trim($_POST['email']),$_POST['content']);
		setcookie('email',trim($_POST['email']));
		if($result) {
			echo "<script type=\"text/javascript\">alert('举报成功，我们会尽快处理，感谢您的参与！');</script>";	
			echo "<script type=\"text/javascript\">window.location.href='$_SERVER[HTTP_REFERER]';</script>";	
		} else {
			echo "<script type=\"text/javascript\">alert('此文件已经被举报，我们正在处理，感谢您的参与！');</script>";	
			echo "<script type=\"text/javascript\">window.location.href='$_SERVER[HTTP_REFERER]';</script>";			
		}
	}
	/*页面输出判断*/
	/*如果有页面输出，输出页面。*/
	if(!empty($FFS['html'])) {
		HTML_LOAD(ROT.'glob/res/index.html',$FFS['html']['path']);
		HTML_CONTENT($FFS['html']['tag']);
		HTML_PUT();
	}
}

