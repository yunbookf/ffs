<?php
if(RUN&&ADMIN){
	include_once(MOP.'config.php');
	if(ACT=='index'){
				/*创建页面数组*/
		$FFS['html']['path']=MOP.'admin.html';
        $FFS['html']['tag']['{html:FILE_DMT}'] = FILE_DMT;
		$FFS['html']['tag']['{html:FILE_DSP}'] = FILE_DSP;
		$FFS['html']['tag']['{html:F_LINK_COUNTDOWN}'] = F_LINK_COUNTDOWN;
		$FFS['html']['tag']['{html:SITEDOWN_TURN}'] = SITEDOWN_TURN;
		$FFS['html']['tag']['{html:F_AD}'] = stripslashes(F_AD);
		$FFS['html']['tag']['{html:LDN}'] = stripslashes(LDN);
		$FFS['html']['tag']['{html:SITEBAN_LIST}'] = stripslashes(SITEBAN_LIST);
		if(SITELIST_BAN==0){
			$FFS['html']['tag']['{html:SITELIST_BANA}'] = ' checked="checked"';
			$FFS['html']['tag']['{html:SITELIST_BANB}'] = '';
			}
		else{
			$FFS['html']['tag']['{html:SITELIST_BANA}'] = '';
			$FFS['html']['tag']['{html:SITELIST_BANB}'] = ' checked="checked"';			
			}
		if(!empty($_POST['down_update'])){
		$context = '<?php '."\n";
		unset($_POST['down_update']);
		unset($_POST['try_ldn']);
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
			FILE_SYNC(trim($_POST['LDN']));
			STR_EDITNOTICE('file_config_update_success');
		}else{
			
		}
		
			}
		elseif(!empty($_POST['try_ldn'])){
			echo FILE_SYNC_TRY(trim($_POST['LDN']));
			STR_EDITNOTICE('');
			}
	}
}
?>