<?php
if(RUN&&ADMIN){
	include_once(MOP.'config.php');
	if(ACT=='index'){
				/*创建页面数组*/
		$FFS['html']['path']=MOP.'admin.html';
        $FFS['html']['tag']['{html:UPLOAD_SIZE}'] = UPLOAD_SIZE;
		$FFS['html']['tag']['{html:UPLOAD_TYPE}'] = UPLOAD_TYPE;
		$FFS['html']['tag']['{html:UPLOAD_MAX}'] = UPLOAD_MAX;
		$FFS['html']['tag']['{html:UPLOAD_EMAIL}'] = UPLOAD_EMAIL;
		$FFS['html']['tag']['{html:NOW_UPLOAD_SIZE}']=ini_get('upload_max_filesize') > ini_get('post_max_size') ? ini_get('post_max_size') : ini_get('upload_max_filesize');
		if($_POST){
		$context = '<?php '."\n";
		unset($_POST['up_update']);
		$post = array_keys($_POST);
		foreach($post as $key){
			if($key=='SAVE_DIR')rename(SYS,ROT.$_POST[$key]);
			if(!get_magic_quotes_gpc()){
			$context=$context.'define(\''.strtoupper($key).'\',\''.addslashes($_POST[$key]).'\'); '."\n";
			}else{
				$context=$context.'define(\''.strtoupper($key).'\',\''.$_POST[$key].'\'); '."\n";
				}
		}
		$context=$context.' ?>';
		if(file_put_contents(MOP.'config.php',$context)){
			chmod(MOP.'config.php',0777);
			STR_EDITNOTICE('upload_config_update_success');
		}else{
			STR_EDITNOTICE('upload_config_update_false');
		}
			}
	}
}
?>