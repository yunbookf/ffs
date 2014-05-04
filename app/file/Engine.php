<?php
/*处理处理VAL*/
if(RUN){
	if(VERSION('c',120226)){
	include_once(MOP.'config.php');
	if(VAL=='sync'){
		echo file_get_contents(MOP.'config.php')."\n<?php\ndefine('PREFIX_AD','".PREFIX_AD."');\n?>";
	}else{
	$info = explode('-',VAL);
	$do = $info[0];
	$id = explode('.',$info[1]);
	$id = $id[0];
	if(preg_match("/[ '.,:;*?~`!@#$%^&+=)(<>{}]|\]|\[|\/|\\\|\"|\|/",$id))ERROR('提取文件出错拉！','您输入的文件分享码包含非法字符串，请检查。');
	if($do == 'view'){
        if($id=='')ERROR('分享码无效','该文件分享码错误，可能不是正确的分享码，或者对应文件已被删除。');
		$info = FILE_REINFO($id);
		if($info==false)ERROR('分享码无效','该文件分享码错误，可能不是正确的分享码，或者对应文件已被删除。');
		/*生成，猜你喜欢*/
		$data = FILE_SEARCH('type',$info['type']);
		if(count($data)<3){
			$rands = count($data);
		}else{
			$rands = 3;
		}
		$rand = array_rand($data,$rands);
		$yl = '';
		$name = '';
		if($rand!="0"){
		foreach($rand as $like){
			$yl = $yl.'<li><a href="?/file/view-'.$data[$like]['id'].'.html">'.$data[$like]['name'].'</a></li>';
		}}else
		{
			$yl="猜不到你喜欢的";
			}
		/*star html*/
		if($info['info'] == 'none'||$info['info']=="")$info['info'] = $info['name'];
		$FFS['html']['path'] = MOP.'file.html';
		$FFS['html']['tag']['{html:title}'] = $info['name'].'--文件分享页--'.SITE_NAM;
		$FFS['html']['tag']['{html:keywords}'] = SITE_KEY;
		$FFS['html']['tag']['{html:des}']  = $info['name'].'--文件分享页--'.SITE_NAM;
		$FFS['html']['tag']['{html:tongji}'] = stripslashes(SITE_TONGJI);
		$FFS['html']['tag']['{html:ad}'] = stripslashes(F_AD);
		$FFS['html']['tag']['{html:ICP}'] = SITE_ICP;
		$FFS['html']['tag']['{file:name}'] = $info['name'];
		$FFS['html']['tag']['{file:quicklink}'] = FILE_QUICK_LINK($info['type'],$info['id'],$info['size'],true);
		$FFS['html']['tag']['{file:size}'] = STR_FILESIZE($info['size']);
		$FFS['html']['tag']['{file:type}'] = $info['type'];
		$FFS['html']['tag']['{file:up}'] = date('Y-m-d',$info['time']);
		$FFS['html']['tag']['{file:info}'] = str_replace("\r","<br />",$info['info']);
		$FFS['html']['tag']['{file:down}'] = $info['down'];
		$FFS['html']['tag']['{file:id}']   = $info['id'];
		$FFS['html']['tag']['{file:infoImg}']   =URL.'?/file/pic-'.$id.'.gif';
		$FFS['html']['tag']['{html:reportKey}'] = STR_CUT_KEY();
		$FFS['html']['tag']['{html:yourEmail}'] =!empty($_COOKIE['email']) ? $_COOKIE['email'] : '' ;
		$FFS['html']['tag']['{file:youlike}']  = $yl;
		$FFS['html']['tag']['{file:LINK_COUNTDOWN}']  = F_LINK_COUNTDOWN;
        $FFS['html']['tag']['{html:favourite}']= file_exists(ROT.'app/manage/Engine.php') ? '<a href="'.URL.'?/manage/tofavourite_'.$info['id'].'" title="收藏此文件到我的收藏夹">[我要收藏]</a>'   : '' ;
		/*处理下载链接*/
		$downlink="";
		if(LDN!=""){
		$siteStr=explode("\n",LDN);
		foreach($siteStr as $siteVal){
			$siteInfo=explode('|',$siteVal);
			$downlink=$downlink.'<a href="http://'.$siteInfo['1'].'/d.php?file'.time().$id.'.'.$info['type'].'" title="'.$siteInfo['0'].'">'.$siteInfo['0'].'</a>';
			}
		}
		$downlink=(SITEDOWN_TURN==1 ? $downlink='<a href="'.URL.'d.php?file'.time().$id.'.'.$info['type'].'" title="本站下载">本站下载</a>'.$downlink : $downlink);
		$FFS['html']['tag']['{file:downlink}'] = $downlink;
		$FFS['html']['tag']['{file:viewlink}'] = URL.'?/file/view-'.$id.'.html';
		/*end html*/
	}elseif($do=='edit'){
		$id=trim($_POST['info_us']);
		$info = FILE_REINFO($id);
		if($info==false)ERROR('分享码无效','该文件分享码错误，可能不是正确的分享码，或者对应文件已被删除。');
		/*检测密码是否正确*/
		if(!empty($_POST['info_pw'])){
			if($info['pw']!=trim($_POST['info_pw'])){
				ERROR('登陆错误','分享码或管理密码错误！');
				}
			else{ //密码正确
				if(!empty($_POST['fileEdit'])){ //如果获取到是修改文件信息的提交
					$info['name']=trim($_POST['info_name']);
					$info['pw']=trim($_POST['info_pw']);
					$info['info']=trim($_POST['info_info']);
					FILE_MKINFO($info);
					}
				if(!empty($_POST['fileDel'])){
					FILE_DELETE($info['id']);
					header('location:index.php');
					}
				}
			}
		else{
			ERROR('警告','请通过正确的方式来访问本页面！');
			}
		
		/*生成，猜你喜欢*/
		$data = FILE_SEARCH('type',$info['type']);
		if(count($data)<3){
			$rands = count($data);
		}else{
			$rands = 3;
		}
		$rand = array_rand($data,$rands);
		$yl = '';
		$name = '';
		if($rand!="0"){
		foreach($rand as $like){
			$yl = $yl.'<li><a href="?/file/view-'.$data[$like]['id'].'.html">'.$data[$like]['name'].'</a></li>';
		}}else
		{
			$yl="猜不到你喜欢的";
			}
		/*star html*/
		if($info['info'] == 'none'||$info['info']=="")$info['info'] = $info['name'];
		$FFS['html']['path'] = MOP.'fileEdit.html';
		$FFS['html']['tag']['{html:title}'] = $info['name'].'--文件编辑--'.SITE_NAM;
		$FFS['html']['tag']['{html:keywords}'] = SITE_KEY;
		$FFS['html']['tag']['{html:des}']  = $info['name'].'--文件编辑--'.SITE_NAM;
		$FFS['html']['tag']['{html:tongji}'] = stripslashes(SITE_TONGJI);
		$FFS['html']['tag']['{html:ad}'] = stripslashes(F_AD);
		$FFS['html']['tag']['{html:ICP}'] = SITE_ICP;
		$FFS['html']['tag']['{file:name}'] = $info['name'];
		$FFS['html']['tag']['{file:pw}'] = $info['pw'];
		$FFS['html']['tag']['{file:size}'] = STR_FILESIZE($info['size']);
		$FFS['html']['tag']['{file:type}'] = $info['type'];
		$FFS['html']['tag']['{file:up}'] = date('Y-m-d',$info['time']);
		$FFS['html']['tag']['{file:info}'] = $info['info'];
		$FFS['html']['tag']['{file:down}'] = $info['down'];
		$FFS['html']['tag']['{file:id}']   = $info['id'];
		$FFS['html']['tag']['{file:infoImg}']   =URL.'?/file/pic-'.$id.'.gif';
		$FFS['html']['tag']['{html:reportKey}'] = STR_CUT_KEY();
		$FFS['html']['tag']['{html:yourEmail}'] =!empty($_COOKIE['email']) ? $_COOKIE['email'] : '' ;
		$FFS['html']['tag']['{file:youlike}']  = $yl;
		$FFS['html']['tag']['{file:LINK_COUNTDOWN}']  = F_LINK_COUNTDOWN;
		/*处理下载链接*/
		$downlink="";
		if(LDN!=""){
		$siteStr=explode("\n",LDN);
		foreach($siteStr as $siteVal){
			$siteInfo=explode('|',$siteVal);
			$downlink=$downlink.'<a href="http://'.$siteInfo['1'].'/d.php?file'.time().$id.'.'.$info['type'].'" title="'.$siteInfo['0'].'">'.$siteInfo['0'].'</a>';
			}
		}
		$dowlink=(SITEDOWN_TURN==1 ? $downlink='<a href="'.URL.'d.php?file'.time().$id.'.'.$info['type'].'" title="本站下载">本站下载</a>'.$downlink : $downlink);
		
		
		$FFS['html']['tag']['{file:downlink}'] = $dowlink;
		$FFS['html']['tag']['{file:fdownlink}'] = URL.'d.php?file'.time().$id.'.'.$info['type'];
		$FFS['html']['tag']['{file:viewlink}'] = URL.'?/file/view-'.$id.'.html';
		/*end html*/
		
		/*获取编辑文件的提交申请*/
		if(!empty($_POST['fileEdit'])){
			
			
			
			
			}
		
		
	}elseif($do=='pic'){
		/*以下代码用于生成图片*/
		$file=FILE_REINFO($id);
		$file['name']=CUT_STR($file['name'],14);
		$text=$file['name'].'.'.$file['type']."\n".'分享码：'.$file['id']."\n".'文件大小：'.STR_FILESIZE($file['size'])."\n".'上传时间：'.date('Y-m-d H:i',$file['time'])."\n".'下载次数：'.$file['down'];
		$bg = MOP.'img.gif';
		$fontfile=FONTFILE;
		FILE_CREATE_IMG($bg,12,0,90,25,$fontfile,$text);
	}elseif($do=='query'){
		/*以下代码用于同步记录*/
		echo serialize(FILE_REINFO($id));
	}elseif($do=='update'){
		/*以下代码用于计算下载次数*/
		$file=array();
		$file=FILE_REINFO($id);
		if(count($file)==11){
			$file['down']=$file['down']+1;
			$file['last'] = mktime();
			$fileDbPath = FILE_MKPATH($id, false) . $id.'.dbs';
			file_put_contents($fileDbPath, serialize($file));
			chmod($fileDbPath, 0777);
			}
	}else{
		$nowtime = time();
		$gettime = $do;
		$maxtime = $nowtime+FILE_DMT*24*3600;	
		if($maxtime>$gettime  && ($nowtime-$gettime)<FILE_DMT*24*3600){
			$info = FILE_REINFO($id);
			FILE_OUTPUT($info,FILE_DSP);
		}else{
			ERROR('该文件下载链接已过期','<a href="'.URL.'?/file/view-'.$id.'.html'.'">点击此处重新提取文件。</a>');
		}
	}
	}
		}
		else{
			ERROR('运行提示','当前系统核心版本过低，请升级到C-120226');
			}
}
?>