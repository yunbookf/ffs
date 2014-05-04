<?php
if(RUN){
	if(VERSION('c',120226)){
	include_once MOP.'config.php';
	if(VAL==''){
		ERROR('访问出错','请检查您的URL路径');
	}else{
		/*预处理VAL*/
		$val = explode('-',VAL);
		if($val[0]=='swf'){
			/*导入预览页*/
			$info = FILE_REINFO(substr($val[1],0,-5));
			if($info==false){
				ERROR('提取错误啦','该分享码无效，分享码不存在。');
			}elseif($info['type']!='swf'){
				ERROR('提取错误啦','只能分享SWF格式的文件哦！');
			}elseif($info['size']>SWF_LIT*1024*1024){
				ERROR('提取错误啦','目前只能分享小于'.STR_FILESIZE(SWF_LIT*1024*1024).'的文件');
			}else{
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
					$yl = $yl.'<li><a href="?/swf/swf-'.$data[$like]['id'].'.html">'.$data[$like]['name'].'</a></li>';
				}}else
				{
					$yl="猜不到你喜欢的";
					}
				if($info['info'] == 'none'||$info['info']=="")$info['info'] = $info['name'];
			    $FFS['html']['path'] = MOP.'swf.html';
	            $FFS['html']['tag']['{html:title}'] = $info['name'].'--动画分享页--'.SITE_NAM;
	            $FFS['html']['tag']['{html:keywords}'] = SITE_KEY;
	            $FFS['html']['tag']['{html:des}'] = $info['name'].'--动画分享页--'.SITE_NAM;
				$FFS['html']['tag']['{html:tongji}'] = stripslashes(SITE_TONGJI);
				$FFS['html']['tag']['{html:ad}'] = stripslashes(AD);
				$FFS['html']['tag']['{html:ICP}'] = SITE_ICP;
				$FFS['html']['tag']['{html:name}'] = SITE_NAM;
				$FFS['html']['tag']['{file:name}']=$info['name'];
				$FFS['html']['tag']['{file:id}']  =$info['id'];
				$FFS['html']['tag']['{html:reportKey}'] = STR_CUT_KEY();
				$FFS['html']['tag']['{html:yourEmail}'] =!empty($_COOKIE['email']) ? $_COOKIE['email'] : '' ;
				$FFS['html']['tag']['{file:youlike}']  = $yl;
				$FFS['html']['tag']['{file:LINK_COUNTDOWN}']  = LINK_COUNTDOWN;
				$FFS['html']['tag']['{html:days}'] = SWF_LMT;
				$FFS['html']['tag']['{file:down}']=$info['down'];
				$FFS['html']['tag']['{file:info}']=$info['info'];
				$FFS['html']['tag']['{file:size}']=STR_FILESIZE($info['size']);
				$FFS['html']['tag']['{file:time}']= date('Y-m-d',$info['time']);
				$FFS['html']['tag']['{link:down}']=URL.'d.php?swfd'.time().$info['id'].'.swf';
				$FFS['html']['tag']['{link:link}']=URL.'d.php?swfl'.time().$info['id'].'.swf';
				$FFS['html']['tag']['{link:view}']=URL.'?/swf/swf-'.$info['id'].'.html';
				$FFS['html']['tag']['{html:favourite}']= file_exists(ROT.'app/manage/Engine.php') ? '<a href="'.URL.'?/manage/tofavourite_'.$info['id'].'" title="收藏此文件到我的收藏夹">[我要收藏]</a>'   : '' ;
			}
		}
		if($val[0]=='link'){
			$gettime = substr($val[1],10);
			$id      = substr($val[1],10,-4);
			$nowtime = time();
			$maxtime = $nowtime+SWF_LMT*24*3600;
			$info    = FILE_REINFO($id);
			if($info==false){
				ERROR('提取错误啦','该分享码无效');
			}elseif($info['type']!='swf'){
				ERROR('提取错误啦','只能分享SWF格式的文件哦！');
			}elseif($info['size']>SWF_LIT*1024*1024){
				ERROR('提取错误啦','目前只能分享小于'.STR_FILESIZE(SWF_LIT*1024*1024).'的文件');
			}elseif($maxtime<$gettime  && ($nowtime-$gettime)>SWF_LMT*24*3600){
				ERROR('该文件下载链接已过期','<a href="'.URL.'?/swf/swf-'.$id.'.html'.'">点击此处重新提取文件。</a>');
			}else{
				FILE_OUTPUT($info,SWF_DSP,true);
			}
		}
		if($val[0]=='down'){
			$gettime = substr($val[1],10);
			$id      = substr($val[1],10,-4);
			$nowtime = time();
			$maxtime = $nowtime+SWF_DMT*24*3600;
			$info    = FILE_REINFO($id);
			if($info==false){
				ERROR('提取错误啦','该分享码无效');
			}elseif($info['type']!='swf'){
				ERROR('提取错误啦','只能分享SWF格式的文件哦！');
			}elseif($info['size']>SWF_LIT*1024*1024){
				ERROR('提取错误啦','目前只能分享小于'.STR_FILESIZE(SWF_LIT*1024*1024).'的文件');
			}elseif($maxtime<$gettime  && ($nowtime-$gettime)>SWF_DMT*24*3600){
				ERROR('该文件下载链接已过期','<a href="'.URL.'?/swf/swf-'.$id.'.html'.'">点击此处重新提取文件。</a>');
			}else{
				FILE_OUTPUT($info,SWF_DSP);
			}
		}
	}
	}
	else{
		ERROR('运行提示','当前系统核心版本过低，请升级到C-120226');
		}
}
?>