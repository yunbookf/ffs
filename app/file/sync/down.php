<?php
//下载文件
set_time_limit(0);
ignore_user_abort(true); 
$baseUrl=$_POST['baseUrl'];
$fpath=$_POST['fpath'];
$lpath=$_POST['lpath'];
$bpath=$_POST['bpath'];


$res = file_get_contents($baseUrl);
if ($res !== false) {
file_put_contents($fpath, $res);
		unlink($lpath);
		exit;
} else {
		unlink($lpath);
		unlink($bpath);
		exit;
}
?>