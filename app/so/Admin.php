<?php
if(RUN&&ADMIN){
	include_once(MOP.'config.php');
	if(ACT=='index'){
		/*创建页面数组*/
		$FFS['html']['path']=MOP.'admin.html';
        $FFS['html']['tag']['{html:SEARCH_NUM}'] = SEARCH_NUM;
		$FFS['html']['tag']['{html:SO_MAX}'] = SO_MAX;

		if($_POST){
		$context = '<?php '."\n";
		unset($_POST['so_update']);
		$post = array_keys($_POST);
		foreach($post as $key){
			if(!get_magic_quotes_gpc()){
			$context=$context.'define(\''.strtoupper($key).'\',\''.addslashes($_POST[$key]).'\'); '."\n";
			}else{
				$context=$context.'define(\''.strtoupper($key).'\',\''.$_POST[$key].'\'); '."\n";
				}
		}
		$context=$context.' ?>';
		if(file_put_contents(MOP.'config.php',$context)){
			chmod(MOP.'config.php',0777);
			STR_EDITNOTICE('so_config_update_success');
		}else{
			STR_EDITNOTICE('so_config_update_false');
		}
			}
	}
}
?>