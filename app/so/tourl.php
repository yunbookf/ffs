<?php
header("content-Type: text/html; charset=utf-8");
if(!$_POST){exit;}
$soText=urlencode(trim($_POST['soText']));
if($_POST['inStie']){
	$url='../../?/so/'.$soText.'&page=1';
	}
elseif($_POST['allSite']){
	$url='frame.php?q='.$soText;
	}
else{
	$url='../../?/so/'.$soText.'&page=1';
	}
header('location:'.$url);
?>