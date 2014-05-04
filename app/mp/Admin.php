<?php
if(RUN&&ADMIN){
	include_once(MOP.'config.php');
	if(ACT=='index'){
				/*创建页面数组*/
		$FFS['html']['path']=MOP.'admin.html';
        $FFS['html']['tag']['{html:PLAY_LIT}'] = PLAY_LIT;
		$FFS['html']['tag']['{html:PLAY_DMT}'] = PLAY_DMT;
		$FFS['html']['tag']['{html:LINK_COUNTDOWN}'] = LINK_COUNTDOWN;
		$FFS['html']['tag']['{html:PLAY_DSP}'] = PLAY_DSP;
		$FFS['html']['tag']['{html:PLAY_LSP}'] = PLAY_LSP;
		$FFS['html']['tag']['{html:PLAY_LMT}'] = PLAY_LMT;
		$FFS['html']['tag']['{html:AD}'] = stripslashes(AD);
		if($_POST){
		$context = '<?php '."\n";
		unset($_POST['mp_update']);
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
			STR_EDITNOTICE('The_Action_Is_Ok!');
		}else{
			
		}
			}
	}
}
?>