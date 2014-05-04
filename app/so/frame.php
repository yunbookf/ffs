<?php
include('../../config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAM;?>—提供动画、音乐、图片等基于FFS文件分享系统的文件搜索。</title>
<style type="text/css">
*{ margin:0; padding:0;}
html{ height:100%; width:100%;}
body{ width:100%; height:100%; overflow:hidden; position:relative;}
.bar{ height:32px; width:100%; background:url(icon.png) repeat-x left bottom; position:absolute; top:0; left:0;}
.logo{ padding-left:10px;}
.close,.back{ display:block; float:right; width:18px; height:18px; overflow:hidden; background:url(icon.png) no-repeat; margin-right:15px; margin-top:5px; text-indent:-9999px;}
.close{ background-position: left -18px;}
img{ border:none; border:0;}
iframe{ border:none; margin-top:32px; width:100%; height:100%;}
</style>
</head>
<?php
error_reporting(0);
?>
<body>
<div class="bar">
<a href="javascript:window.close();" class="close" title="关闭当前页">关闭</a><a href="<?php echo $_SERVER['HTTP_REFERER'];?>" class="back" title="返回首页">返回</a><h1 class="logo"><a href="http://so.fps88.com/"><img src="allLogo.gif" alt="" /></a></h1>
</div>
<iframe src="http://so.fps88.com/search.php?searchword=<?php echo $_GET['q'];?>"></iframe>
</body>
</html>