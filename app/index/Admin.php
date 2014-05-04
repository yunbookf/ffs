<?php
if(RUN&&ADMIN){
	include_once(MOP.'config.php');
	if(ACT=='index'){
				/*创建页面数组*/
		$FFS['html']['path']=MOP.'admin.html';
		$FFS['html']['tag']['{html:INDEX_INFO}'] = stripslashes(INDEX_INFO);
		$FFS['html']['tag']['{html:INDEX_AD}'] = stripslashes(INDEX_AD);
		$FFS['html']['tag']['{html:INDEX_UPDATE}'] = INDEX_UPDATE;
		$FFS['html']['tag']['{html:INDEX_UPDATE_NUMS}'] = INDEX_UPDATE_NUMS;
		$FFS['html']['tag']['{html:INDEX_FRIENDLINK_TURN}'] = INDEX_FRIENDLINK_TURN;
		$FFS['html']['tag']['{html:INDEX_FRIENDLINK}'] = INDEX_FRIENDLINK;
		if($_POST){
		$context = '<?php '."\n";
		unset($_POST['index_update']);
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
			STR_EDITNOTICE('index_config_update_success');
		}else{
			
		}
			}
	}
}
?>