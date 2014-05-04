<?php
if(RUN&&ADMIN){
	include_once(MOP.'config.php');
	if(ACT=='index'){
				/*创建页面数组*/
		$FFS['html']['path']=MOP.'admin.html';
        $FFS['html']['tag']['{html:PIC_LIT}'] = PIC_LIT;
		$FFS['html']['tag']['{html:PIC_DMT}'] = PIC_DMT;
		$FFS['html']['tag']['{html:LINK_COUNTDOWN}'] = LINK_COUNTDOWN;
		$FFS['html']['tag']['{html:PIC_DSP}'] = PIC_DSP;
		$FFS['html']['tag']['{html:PIC_DSP_LINK}'] = PIC_DSP_LINK;
		$FFS['html']['tag']['{html:PIC_F_EXPIRED}'] = PIC_F_EXPIRED;
		$FFS['html']['tag']['{html:P_AD}'] = stripslashes(P_AD);
		if($_POST){
		$context = '<?php '."\n";
		unset($_POST['pic_update']);
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
			STR_EDITNOTICE('pic_config_update_success');
		}else{
			
		}
			}
	}
}
?>