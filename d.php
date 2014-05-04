<?php
if(!empty($_GET)){
	include('glob.php');
	foreach($_GET as $key => $value){
		$link=$key;
		}
	$link=explode('_',$link);
	
	/*文件下载*/
	if(strpos($link[0],'file')===0){
		include('app/file/config.php');
		$nowtime = mktime();
		$gettime = substr($link[0],4,10);
		$id= substr($link[0],14,7);
		$maxtime = $nowtime+FILE_DMT*24*3600;	
			if($maxtime>$gettime && ($nowtime-$gettime)<FILE_DMT*24*3600){
				$info = FILE_REINFO($id);
				if($info==false){ERROR('提取错误啦','该分享码无效，分享码不存在活已被删除。');}
				FILE_OUTPUT($info,FILE_DSP);
			}else{
				ERROR('该文件下载链接已过期','<a href="'.URL.'?/file/view-'.$id.'.html'.'">点击此处重新提取文件。</a>');
			}
		}

	/*SWF文件下载*/
	
	/*本站加载*/
	elseif(strpos($link[0],'swfd')===0){
		include('app/swf/config.php');
			if($_SERVER['HTTP_REFERER']){
			if(!stristr($_SERVER['HTTP_REFERER'].'/',URL)){
				$file['path']="app/swf/banOutside.swf";
				$file['name']="本链接禁止外链";
				$file['mime']="application/x-shockwave-flash";
				FILE_REDOWN($file,true);
				exit;
				}
			}
		$nowtime = mktime();
		$gettime = substr($link[0],4,10);
		$id      = substr($link[0],14,7);
		$maxtime = $nowtime+SWF_DMT*24*3600;
		$info    = FILE_REINFO($id);
			if($info==false){
				ERROR('提取错误啦','该分享码无效或已被删除');
			}elseif($info['type']!='swf'){
				ERROR('提取错误啦','只能分享SWF格式的文件哦！');
			}elseif($info['size']>SWF_LIT*1024*1024){
				ERROR('提取错误啦','目前只能分享小于'.STR_FILESIZE(SWF_LIT*1024*1024).'的文件');
			}else{
				FILE_OUTPUT($info,SWF_DSP,true);
			}
		}		
	/*站外加载*/
	elseif(strpos($link[0],'swfl')===0){
		include('app/swf/config.php');
		$nowtime = mktime();
		$gettime = substr($link[0],4,10);
		$id      = substr($link[0],14,7);
		$maxtime = $nowtime+SWF_DMT*24*3600;
		$info    = FILE_REINFO($id);
			if($info==false){
				ERROR('提取错误啦','该分享码无效或已被删除');
			}elseif($info['type']!='swf'){
				ERROR('提取错误啦','只能分享SWF格式的文件哦！');
			}elseif($info['size']>SWF_LIT*1024*1024){
				ERROR('提取错误啦','目前只能分享小于'.STR_FILESIZE(SWF_LIT*1024*1024).'的文件');
			}else{
				FILE_OUTPUT($info,SWF_LSP,true);
			}
		}	

	/*图片文件下载*/
	
	/*本站加载*/
	elseif(strpos($link[0],'picd')===0){
			include('app/pic/config.php');
			if($_SERVER['HTTP_REFERER']){
			if(!stristr($_SERVER['HTTP_REFERER'].'/',URL)){
				$file['path']="app/pic/banOutside.gif";
				$file['name']="本链接禁止外链";
				$file['mime']="image/jpg";
				FILE_REDOWN($file,true);
				exit;
				}
			}
			$nowtime = mktime();
			$gettime = substr($link[0],4,10);
			$id      = substr($link[0],14,7);
			$maxtime = $nowtime+PIC_DMT*24*3600;
			$info    = FILE_REINFO($id);
			if($info==false){
				ERROR('提取错误啦','该分享码无效或已被删除。');
			}elseif($info['type']!='jpg'&&$info['type']!='gif'&&$info['type']!='png'){
				ERROR('提取错误啦','只能分享图片格式的文件哦！');
			}elseif($info['size']>PIC_LIT*1024*1024){
				ERROR('提取错误啦','目前只能分享小于'.STR_FILESIZE(PIC_LIT*1024*1024).'的文件');
			}else{
				FILE_OUTPUT($info,PIC_DSP);
			}
		}	
	/*站外加载*/
	elseif(strpos($link[0],'picl')===0){
			include('app/pic/config.php');
			
			function PIC_EXPIRED($id,$header,$mod){
				$dbPath=FILE_IDPATH($id,$header,$mod).$id.'.db';
				$now=mktime();
				if(!file_exists($dbPath)){
					$content['A-D']=PIC_DMT*24*3600;
					$content['N-D']=$now;
					$content['A-F']=PIC_F_EXPIRED*1024*1024;
					$content['N-F']=0;
					file_put_contents($dbPath,serialize($content));
					}
				$check=unserialize(file_get_contents($dbPath));
				if(($now-$check['N-D'])>PIC_DMT*24*3600){
					$content['A-D']=PIC_DMT*24*3600;
					$content['N-D']=$now;
					$content['A-F']=PIC_F_EXPIRED*1024*1024;
					$content['N-F']=0;
					file_put_contents($dbPath,serialize($content));
					}
				elseif($check['N-F']>=$check['A-F']&&($now-$check['N-D'])<PIC_DMT*24*3600){
					$EXPIRED='YES';
					return $EXPIRED;
					}
				elseif($check['N-F']<$check['A-F']&&($now-$check['N-D'])<PIC_DMT*24*3600){
					$file=FILE_REINFO($id);
					$check['N-F']=$check['N-F']+$file['size'];
					file_put_contents($dbPath,serialize($check));
					}
				}			
			
			$nowtime = mktime();
			$gettime = substr($link[0],4,10);
			$id      = substr($link[0],14,7);
			$maxtime = $nowtime+PIC_DMT*24*3600;
			$info    = FILE_REINFO($id);
			$EXPIRED=PIC_EXPIRED($info['id'],'app/pic/flowRecord/',true);
			if($info==false){
				ERROR('提取错误啦','该分享码无效或已被删除');
			}elseif($info['type']!='jpg'&&$info['type']!='gif'&&$info['type']!='png'){
				ERROR('提取错误啦','只能分享图片格式的文件哦！');
			}elseif($info['size']>PIC_LIT*1024*1024){
				ERROR('提取错误啦','目前只能分享小于'.STR_FILESIZE(PIC_LIT*1024*1024).'的文件');
			}elseif($maxtime<$gettime  && ($nowtime-$gettime)>PIC_DMT*24*3600){
				ERROR('该文件下载链接已过期','<a href="'.URL.'?/pic/pic-'.$id.'.html'.'">点击此处重新提取文件。</a>');
			}elseif($EXPIRED=='YES' ){
				$file['path']="app/pic/flowOver.gif";
				$file['name']="超出限制";
				$file['mime']="'image/png";
				FILE_REDOWN($file,true);
				}
			else{
				FILE_OUTPUT($info,PIC_DSP_LINK,true);
			}
		}	
		

	/*音乐文件下载*/
	
	/*本站加载*/
	elseif(strpos($link[0],'mp3d')===0){
			include('app/mp/config.php');
			if($_SERVER['HTTP_REFERER']){
				if(!stristr($_SERVER['HTTP_REFERER'].'/',URL)){
				$file['path']="app/mp/banOutside.mp3";
				$file['name']="本链接禁止外链";
				$file['mime']="audio/mpeg";
				FILE_REDOWN($file,true);
				exit;
					}
			}
			$nowtime = mktime();
			$gettime = substr($link[0],4,10);
			$id      = substr($link[0],14,7);
			$maxtime = $nowtime+PLAY_DMT*24*3600;
			$info    = FILE_REINFO($id);
			if($info==false){
				ERROR('提取错误啦','该分享码无效或已经被删除');
			}elseif($info['type']!='mp3'){
				ERROR('提取错误啦','只能分享MP3格式的文件哦！');
			}elseif($info['size']>PLAY_LIT*1024*1024){
				ERROR('提取错误啦','目前只能分享小于'.STR_FILESIZE(PLAY_LIT*1024*1024).'的文件');
			}else{
				FILE_OUTPUT($info,PLAY_DSP);
			}
		}	
	/*站外加载*/
	elseif(strpos($link[0],'mp3l')===0){
			include('app/mp/config.php');
			$nowtime = mktime();
			$gettime = substr($link[0],4,10);
			$id      = substr($link[0],14,7);
			$maxtime = $nowtime+PLAY_DMT*24*3600;
			$info    = FILE_REINFO(strtoupper($id));
			if($info==false){
				ERROR('提取错误啦','该分享码无效或已被删除。');
			}elseif($info['type']!='mp3'){
				ERROR('提取错误啦','只能分享MP3格式的文件哦！');
			}elseif($info['size']>PLAY_LIT*1024*1024){
				ERROR('提取错误啦','目前只能分享小于'.STR_FILESIZE(PLAY_LIT*1024*1024).'的文件');
			}elseif($maxtime<$gettime  && ($nowtime-$gettime)>PLAY_LMT*24*3600){
				ERROR('该文件下载链接已过期','<a href="'.URL.'?/mp/play-'.$id.'.html'.'">点击此处重新提取文件。</a>');
			}else{
				FILE_OUTPUT($info,PLAY_LSP);
			}		
		}		
	/*站外随机加载列表*/
	elseif(strpos($link[0],'randomlist')===0){
			include('app/mp/config.php');
			$nowtime = mktime();
			$gettime = substr($link[0],10,10);
			$id      = explode('|',substr($link[0],20,-4));
			shuffle($id);
			$maxtime = $nowtime+PLAY_DMT*24*3600;
			$info    = FILE_REINFO(strtoupper($id[0]));
			if($info==false){
				ERROR('提取错误啦','该分享码无效或已被删除。');
			}elseif($info['type']!='mp3'){
				ERROR('提取错误啦','只能分享MP3格式的文件哦！');
			}elseif($info['size']>PLAY_LIT*1024*1024){
				ERROR('提取错误啦','目前只能分享小于'.STR_FILESIZE(PLAY_LIT*1024*1024).'的文件');
			}elseif($maxtime<$gettime  && ($nowtime-$gettime)>PLAY_LMT*24*3600){
				ERROR('该文件下载链接已过期','<a href="'.URL.'?/mp/play-'.$id.'.html'.'">点击此处重新提取文件。</a>');
			}else{
				FILE_OUTPUT($info,PLAY_LSP);
			}		
		}		
			
	}
else{
	header('location:index.php');
	}





?>