<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>sitemap自动提交工具 —Powered By FFS</title>
<style type="text/css">
*{ margin:0; padding:0;}
html{ background:#f1f1f1;}
body{ background:#fff; border:1px solid #ccc; margin:20px; padding:20px; line-height:25px;}
</style>

</head>

<body>
<p>将此地址复制到地址栏打开即可，<a href="http://www.fps88.com/toSitemap.php">http://www.fps88.com/toSitemap.php?u=$sitemapurl</a>,将$sitemapurl="你的sitemap地址"则可</p>
<p>例如：<a href="http://www.fps88.com/toSitemap.php?url=http://u.fps88.com/sitemap.xml">http://www.fps88.com/toSitemap.php?u=http://u.fps88.com/sitemap.xml</a>，或者把sitemap.xml地址填写入文本框</p>
<p>提交到其他搜索引擎还请自己亲自提交，但也不要频繁提交</p>
<form action="http://www.fps88.com/toSitemap.php">
<input type="text" name="url" value="http://" style="width:300px;" /> <input type="submit" value="提交" />
</form>
<br />
<br />
<?php
$sitemapurl=$_GET["url"];
if($sitemapurl==""){
  print "请填写您的网址...";
  exit();	
}
$pings=array(
			 "ask"=>"http://submissions.ask.com/ping?sitemap=",
			 "bing"=>"http://www.bing.com/webmaster/ping.aspx?siteMap=",
			 "google"=>"http://www.google.com/webmasters/tools/ping?sitemap="
			 );
foreach($pings as $key => $value){
  	$url=$value.$sitemapurl;	
  	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	//$returnCode=curl_exec($ch);
	curl_close($ch);
	echo "已经成功提交到: ".$key ,'<br />';
	
}
?>
<p style="text-align:center;">Powered By <a href="http://www.fps88.com">FFS</a></p>
</body>
</html>