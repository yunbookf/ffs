<?php
if(RUN){
	if(VERSION('c',120226)){
	include_once MOP.'config.php';
	if(VAL==''){
		ERROR('访问出错','请检查您的URL路径');
	}else{
		/*预处理VAL*/
		$val = explode('-',VAL);
		if($val[0]=='play'){
			/*导入预览页*/
			$id   = substr($val[1],0,-5);
			$info = FILE_REINFO($id);
			if($info==false){
				ERROR('提取错误啦','该分享码无效，分享码不存在。');
			}elseif($info['type']!='mp3'){
				ERROR('提取错误啦','只能分享MP3格式的文件哦！');
			}elseif($info['size']>PLAY_LIT*1024*1024){
				ERROR('提取错误啦','目前只能分享小于'.STR_FILESIZE(PLAY_LIT*1024*1024).'的文件');
			}else{
				if($info==false)ERROR('提取出错啦！','您的分享码有误，请仔细检查，或者该分享码对应的文件已被删除。');
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
				$yl = $yl.'<li><a href="?/mp/play-'.$data[$like]['id'].'.html">'.$data[$like]['name'].'</a></li>';
			}}else
			{
				$yl="猜不到你喜欢的";
				}
	            /*star html*/
	            if($info['info'] == 'none'||$info['info']=="")$info['info'] = $info['name'];
	            $FFS['html']['path'] = MOP.'music.html';
	            $FFS['html']['tag']['{html:url}'] = URL;
	            $FFS['html']['tag']['{html:title}'] = $info['name'].'--音乐分享页--'.SITE_NAM;
	            $FFS['html']['tag']['{html:keywords}'] = SITE_KEY;
	            $FFS['html']['tag']['{html:des}']  = $info['name'].'--音乐分享页--'.SITE_NAM;
				$FFS['html']['tag']['{html:tongji}'] = stripslashes(SITE_TONGJI);
				$FFS['html']['tag']['{html:ad}'] = stripslashes(AD);
				$FFS['html']['tag']['{html:ICP}'] = SITE_ICP;
	            $FFS['html']['tag']['{file:name}'] = $info['name'];
	            $FFS['html']['tag']['{file:size}'] = STR_FILESIZE($info['size']);
	            $FFS['html']['tag']['{file:type}'] = $info['type'];
	            $FFS['html']['tag']['{file:info}'] = $info['info'];
	            $FFS['html']['tag']['{file:down}'] = $info['down'];
	            $FFS['html']['tag']['{file:up}']   = date('Y-m-d',$info['time']);
            	$FFS['html']['tag']['{file:id}']   = $info['id'];
				$FFS['html']['tag']['{html:reportKey}'] = STR_CUT_KEY();
				$FFS['html']['tag']['{html:yourEmail}'] =!empty($_COOKIE['email']) ? $_COOKIE['email'] : '' ;
				$FFS['html']['tag']['{file:youlike}']  = $yl;
				$FFS['html']['tag']['{file:LINK_COUNTDOWN}']  = LINK_COUNTDOWN;
				$FFS['html']['tag']['{html:days}'] = PLAY_LMT;
				$FFS['html']['tag']['{file:downlink}'] = URL.'d.php?mp3d'.time().$info['id'].'.mp3';
				$FFS['html']['tag']['{file:link}']     = URL.'d.php?mp3l'.time().$info['id'].'.mp3';
	            $FFS['html']['tag']['{file:viewlink}'] = URL.'?/mp/play-'.$id.'.html';
				$FFS['html']['tag']['{html:favourite}']= file_exists(ROT.'app/manage/Engine.php') ? '<a href="'.URL.'?/manage/tofavourite_'.$info['id'].'" title="收藏此文件到我的收藏夹">[我要收藏]</a>'   : '' ;
	            /*end html*/
				
				/*playlist*/
				if(!empty($_COOKIE['playlist'])){
					/*左边列表*/
					if(!empty($_COOKIE['playListBoxLeft'])){
						$FFS['html']['tag']['{playlist:position}']='left:'.$_COOKIE['playListBoxLeft'].'px;top:'.$_COOKIE['playListBoxTop'].'px;';
						}
					else{
						$FFS['html']['tag']['{playlist:position}']="";
						}
					$FFS['html']['tag']['{playlist:display}']="block";
					$playlistarr=explode('|',$_COOKIE['playlist']);
					$listcount=count($playlistarr);
					$playlist="";
					$mp3list="";
					$randomlist="";
					for($i=0;$i<$listcount-1;$i++){
						$musicinfo=FILE_REINFO($playlistarr[$i]);
						$playlist=$playlist.'
						{
						title:"'.$musicinfo['name'].'",
						dellink:"?/mp/cutmusic-'.$musicinfo['id'].'",
						mp3:"'.URL.'d.php?mp3l'.mktime().$playlistarr[$i].'.mp3"
						},';
						$mp3list=$mp3list.URL.'d.php?mp3l'.mktime().$playlistarr[$i].'.mp3|';
						$randomlist=$randomlist.$playlistarr[$i].'|';
						}
					$FFS['html']['tag']['{playlist:list}']=substr($playlist,0,-1);
					$FFS['html']['tag']['{playlist:mp3list}']=substr($mp3list,0,-1);
					
					/*HTML代码*/
					$FFS['html']['tag']['{playlist:randomlist}']=URL.'?/mp/randomlist-'.mktime().substr($randomlist,0,-1).'.mp3'; //随机播放列表
					
					}
				else{
					$FFS['html']['tag']['{playlist:list}']='' ;
					}
				
			}
		}
		elseif($val[0]=='link'){
			$gettime = substr($val[1],10);
			$id      = substr($val[1],10,-4);
			$nowtime = time();
			$maxtime = $nowtime+PLAY_DMT*24*3600;
			$info    = FILE_REINFO(strtoupper($id));
			if($info==false){
				ERROR('提取错误啦','该分享码无效');
			}elseif($info['type']!='mp3'){
				ERROR('提取错误啦','只能分享MP3格式的文件哦！');
			}elseif($info['size']>PLAY_LIT*1024*1024){
				ERROR('提取错误啦','目前只能分享小于'.STR_FILESIZE(PLAY_LIT*1024*1024).'的文件');
			}elseif($maxtime<$gettime  && ($nowtime-$gettime)>PLAY_LMT*24*3600){
				ERROR('该文件下载链接已过期','<a href="'.URL.'?/mp/play-'.$id.'.html'.'">点击此处重新提取文件。</a>');
			}else{
				FILE_OUTPUT($info,PLAY_LSP);
			}
		}elseif($val[0]=='addmusic'){
			$music=$val[1];
			if(!empty($_COOKIE['playlist'])){
				if(!strstr($_COOKIE['playlist'],$music)){
					$playlist=$_COOKIE['playlist'].$music.'|';
					setcookie('playlist',$playlist,mktime()+3600*24*365);
					}
				}
			else{
				$playlist=$music.'|';
				setcookie('playlist',$playlist,mktime()+3600*24*365);
				}
			header('Location:'.$_SERVER['HTTP_REFERER']);
		}elseif($val[0]=='cutmusic'){
			$music=$val[1].'|';
			$playlist=str_replace($music,'',$_COOKIE['playlist']);
			setcookie('playlist',$playlist);
			if(strlen($_COOKIE['playlist'])==0){unset($_COOKIE['playlist']);}
			header('Location:'.$_SERVER['HTTP_REFERER']);
		}elseif($val[0]=='cutallmusic'){
			setcookie('playlist',"",-1);
			header('Location:'.$_SERVER['HTTP_REFERER']);
		}elseif($val[0]=='randomlist'){
			$gettime = substr($val[1],0,10);
			$id      = explode('|',substr($val[1],10,-4));
			$nowtime = time();
			$maxtime = $nowtime+PLAY_DMT*24*3600;
			shuffle($id);
			$info    = FILE_REINFO(strtoupper($id[0]));
			if($info==false){
				ERROR('提取错误啦','该分享码无效');
			}elseif($info['type']!='mp3'){
				ERROR('提取错误啦','只能分享MP3格式的文件哦！');
			}elseif($info['size']>PLAY_LIT*1024*1024){
				ERROR('提取错误啦','目前只能分享小于'.STR_FILESIZE(PLAY_LIT*1024*1024).'的文件');
			}elseif($maxtime<$gettime  && ($nowtime-$gettime)>PLAY_LMT*24*3600){
				ERROR('该文件下载链接已过期','<a href="'.URL.'?/mp/play-'.$id.'.html'.'">点击此处重新提取文件。</a>');
			}else{
				FILE_OUTPUT($info,PLAY_LSP);
			}
		}elseif($val[0]=='down'){
			$gettime = substr($val[1],10);
			$id      = substr($val[1],10,-4);
			$nowtime = time();
			$maxtime = $nowtime+PLAY_DMT*24*3600;
			$info    = FILE_REINFO($id);
			if($info==false){
				ERROR('提取错误啦','该分享码无效');
			}elseif($info['type']!='mp3'){
				ERROR('提取错误啦','只能分享MP3格式的文件哦！');
			}elseif($info['size']>PLAY_LIT*1024*1024){
				ERROR('提取错误啦','目前只能分享小于'.STR_FILESIZE(PLAY_LIT*1024*1024).'的文件');
			}elseif($maxtime<$gettime  && ($nowtime-$gettime)>PLAY_DMT*24*3600){
				ERROR('该文件下载链接已过期','<a href="'.URL.'?/mp/play-'.$id.'.html'.'">点击此处重新提取文件。</a>');
			}else{
				FILE_OUTPUT($info,PLAY_DSP);
			}
		}
	}
	}
	else{
		ERROR('运行提示','当前系统核心版本过低，请升级到C-120226');
		}
}
?>