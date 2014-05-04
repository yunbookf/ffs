<?php
if(RUN&&ADMIN){
	include_once(MOP.'config.php');
	if(ACT=='index'){
				/*创建页面数组*/
		$FFS['html']['path']=MOP.'admin.html';
        $FFS['html']['tag']['{html:SWF_LIT}'] = SWF_LIT;
		$FFS['html']['tag']['{html:SWF_DMT}'] = SWF_DMT;
		$FFS['html']['tag']['{html:LINK_COUNTDOWN}'] = LINK_COUNTDOWN;
		$FFS['html']['tag']['{html:SWF_DSP}'] = SWF_DSP;
		$FFS['html']['tag']['{html:SWF_LSP}'] = SWF_LSP;
		$FFS['html']['tag']['{html:SWF_LMT}'] = SWF_LMT;
		$FFS['html']['tag']['{html:AD}'] = stripslashes(AD);

		if($_POST){
		$context = '<?php '."\n";
		unset($_POST['swf_update']);
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
			STR_EDITNOTICE('swf_config_update_success');
		}else{
			
		}
			}
	}
}
?>