<?php
if(RUN){
	if(VERSION('c',120226)){
    /*处理处理VAL*/
    if(VAL == 'config'){
	    /*获取上传配置*/
	    $cp = MOP.'config.php';
	    include($cp);
	    $a['upsize'] = UPLOAD_SIZE*1000*1024;
	    $a['uptype'] = UPLOAD_TYPE;
		$a['upmax'] = UPLOAD_MAX;
		$a['upload_email'] = UPLOAD_EMAIL;
		if($_COOKIE['FFS_UC_user']){
			$a['upload_email']='0';
			}		
	    echo json_encode($a);
    }
	if(VAL == 'upload'){
	
	}
	}
	else{
		ERROR('运行提示','当前系统核心版本过低，请升级到C-120226');
		}
}
?>