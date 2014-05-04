<?php
/*使用RUN全局安全常量来确保*/
if(RUN){
	if(VERSION('c',111022)){
	/*此处开始为引擎代码*/	
	include_once('app/index/config.php');
	if(VAL == 'index'){
	    $mods = array_reverse(glob(ROT.'app/*/ico.html'));
	    $FFS['mod_ico'] = '';
	    foreach($mods as $mod){
		    $FFS['mod_ico'] = $FFS['mod_ico'].file_get_contents($mod);
	    }
		
		/*建立要插入的模板*/
		$str1 = '<div class="hotAndNew round_box"><dl class="hotfile"><dt><a href="'.URL.'?/index/hot">热门下载</a></dt>{hot:link}</dl><dl class="newfile"><dt><a href="'.URL.'?/index/new">最近分享</a></dt>{new:link}</dl></div>';
		$data = @file_get_contents(MOP.'update.vds');
		if($data==false || $data=='N'){
			$hot = '暂无数据';
			$new = '暂无数据';
		}else{
			$data = unserialize($data);
			/*开始读取热门下载*/
			$hot = '';
			$name = '';
			foreach($data['hot'] as $hotw){
				$name=$hotw['name'];
				$hot=$hot.'<dd><a href="'.URL.'?/file/view-'.$hotw['id'].'.html" title="'.$name.'.'.$hotw['type'].'">'.$name.'</a><span>'.$hotw['down'].'次</span></dd>';
			}
			/*开始读取最近上传*/
			$new = '';
			foreach($data['new'] as $neww){
				$name=$neww['name'];
				$new=$new.'<dd><a href="'.URL.'?/file/view-'.$neww['id'].'.html" title="'.$name.'.'.$neww['type'].'">'.$name.'</a><span>'.date('Y-m-d',$neww['time']).'</span></dd>';
			}
		}
		$v = array();
		$v['{hot:link}'] = $hot;$v['{new:link}'] = $new;
		$hotAndNew=strtr($str1,$v);		
		
		/*友情链接*/
		if(INDEX_FRIENDLINK_TURN!=""){
			$friendlinkListS=explode("\n",str_replace("\r","",trim(INDEX_FRIENDLINK)));
			$friendlink="";
			foreach($friendlinkListS as $friendlinkList){
				$eachfriendlink=explode('|',$friendlinkList);
				$friendlink=$friendlink.'<a href="'.$eachfriendlink['1'].'" title="'.$eachfriendlink['0'].'" target="_blank">'.$eachfriendlink['0'].'</a>';
				}
			$friendlinkS='<p class="friendlink"><strong>'.INDEX_FRIENDLINK_TURN.'：</strong>'.$friendlink.'</p>';
			}else{
				$friendlinkS="";	
			}
		
		
	    $FFS['html']['path'] = ROT.'app/index/index.html';
	    $FFS['html']['tag']['{html:title}'] = SITE_NAM;
	    $FFS['html']['tag']['{html:keywords}'] = SITE_KEY;
	    $FFS['html']['tag']['{html:des}'] = SITE_DES;
		$FFS['html']['tag']['{html:INDEX_INFO}'] = stripslashes(INDEX_INFO);
		$FFS['html']['tag']['{html:INDEX_AD}'] = stripslashes(INDEX_AD);
		$FFS['html']['tag']['{html:tongji}'] = stripslashes(SITE_TONGJI);
		$FFS['html']['tag']['{html:ICP}'] = SITE_ICP;
		$FFS['html']['tag']['{html:INDEX_FRIENDLINK}'] = $friendlinkS;
	    $FFS['html']['tag']['{html:ico}'] = $FFS['mod_ico'];
		$FFS['html']['tag']['{html:hotAndNew}'] = INDEX_UPDATE ? $hotAndNew : '' ;
		$FFS['html']['tag']['{html:reportKey}'] = "";
		$FFS['html']['tag']['{html:yourEmail}'] =!empty($_COOKIE['email']) ? $_COOKIE['email'] : '' ;
	}elseif(strstr(VAL,'hot')||strstr(VAL,'new')){
		$FFS['html']['path'] = ROT.'app/index/hotNew.html';
	    $FFS['html']['tag']['{html:title}'] = (strstr(VAL,'hot') ? '热门文件' : '最近分享').'--'.SITE_NAM;
		$FFS['html']['tag']['{html:name}'] = SITE_NAM;
	    $FFS['html']['tag']['{html:keywords}'] = SITE_KEY;
	    $FFS['html']['tag']['{html:des}'] = SITE_DES;	
		$FFS['html']['tag']['{html:tongji}'] = stripslashes(SITE_TONGJI);
		$FFS['html']['tag']['{html:ICP}'] = SITE_ICP;
		$FFS['html']['tag']['{html:reportKey}'] = "";
		$FFS['html']['tag']['{html:yourEmail}'] =!empty($_COOKIE['email']) ? $_COOKIE['email'] : '' ;
		$FFS['html']['tag']['{html:viewtype}'] =(strstr(VAL,'hot') ? '热门文件' : '最近分享');	

		if(empty($_GET['page'])){$page="1";}else{$page=$_GET['page'];};
		if(empty($page)||$page<=0)$page=1;
		/*先检索数据*/
		$resa = FILE_READDB();
		$resb = STR_ARRSORT($resa['data'],(strstr(VAL,'hot') ? 'down' : 'time'));
		$resc=count($resb);
		$resc>199 ? $resc=199 : $resc=$resc ;
		for($i=0;$i<$resc;$i++){
			$resd[$i]=$resb[$i];
			}
		/*分页并返回数据*/
		$rese=STR_PAGE($resd,$page,20);
		$res=$rese['data'];
		if($res==false||$resc==0||$resd=="")
		{
				$FFS['html']['tag']['{html:hotNew}']     = "暂无数据";
				$FFS['html']['tag']['{html:pages}']  = '<option>暂无数据</option>';
				$FFS['html']['tag']['{html:nextpage}']  = '';
				$FFS['html']['tag']['{html:previouspage}']  = '';
			}
			else{
		/*处理分页*/
		$pages='';
	    $page_li = '';
	    if($resc>20){
		    $i = 1;
		    $pages = ceil($resc/20);
		    while($i<=$pages){
			    $page_li=$page_li.'<option value="?/index/'.(strstr(VAL,'hot') ? 'hot' : 'new').'&page='.$i.'" '.( $page==$i ? 'selected' : '').'>第['.$i.']页</option>';
			    $i++;
		    }
	    }else{
		    $pages=1;
	    }
			/*上一页 下一页*/
		if($pages==1){$page_li="<option>只有当前页</option>";$nextpage="";$previouspage="";}
		else{
			if(!empty($_GET['page'])){
				$currentpage=$_GET['page'];
				if($currentpage==1){
					$nextpage='<a href="?/index/'.(strstr(VAL,'hot') ? 'hot' : 'new').'&page=2">下一页</a>';
					$previouspage="";
					}
				elseif($currentpage==$pages){
					$nextpage='';
					$previouspage='<a href="?/index/'.(strstr(VAL,'hot') ? 'hot' : 'new').'&page='.($currentpage-1).'">上一页</a>';
					}
				else{
					$nextpage='<a href="?/index/'.(strstr(VAL,'hot') ? 'hot' : 'new').'&page='.($currentpage+1).'">下一页</a>';
					$previouspage='<a href="?/index/'.(strstr(VAL,'hot') ? 'hot' : 'new').'&page='.($currentpage-1).'">上一页</a>';					
					}
				}
			else{
					$nextpage='<a href="?/index/'.(strstr(VAL,'hot') ? 'hot' : 'new').'&page=2">下一页</a>';
					$previouspage="";				
				}
			}
		/*输出数据*/
	$sc = '';
	foreach($res as $key){
		if(strlen($key['name'])>=50){
			$key['name']=CUT_STR($key['name'],40);
			}
		$sc=$sc.'
			 <p class="filename"><a href="'.URL.'?/file/view-'.$key['id'].'.html">'.$key['name'].'</a></p>
			 <p class="fileinfo">分享码：'.$key['id'].' |文件类型：'.$key['type'].' | 文件大小：'.STR_FILESIZE($key['size']).' | 上传时间：'.date('Y-m-d h:i',$key['time']).' | 下载次数：'.$key['down'].'</p>
			';	
	}
		$FFS['html']['tag']['{html:hotNew}']     = $sc;
		$FFS['html']['tag']['{html:pages}']  = $page_li;
		$FFS['html']['tag']['{html:nextpage}']  = $nextpage;
		$FFS['html']['tag']['{html:previouspage}']  = $previouspage;

		}
			
		}
	elseif(VAL == 'agreement.html'){
	    $FFS['html']['path'] = ROT.'app/index/agreement.html';
	    $FFS['html']['tag']['{html:title}'] = '用户条款--'.SITE_NAM;
		$FFS['html']['tag']['{html:name}'] = SITE_NAM;
	    $FFS['html']['tag']['{html:keywords}'] = SITE_KEY;
	    $FFS['html']['tag']['{html:des}'] = SITE_DES;
		$FFS['html']['tag']['{html:tongji}'] = stripslashes(SITE_TONGJI);
		$FFS['html']['tag']['{html:ICP}'] = SITE_ICP;
		$FFS['html']['tag']['{html:reportKey}'] = "";
		$FFS['html']['tag']['{html:yourEmail}'] =!empty($_COOKIE['email']) ? $_COOKIE['email'] : '' ;
	}
	elseif(VAL == 'about.html'){
	    $FFS['html']['path'] = ROT.'app/index/about.html';
	    $FFS['html']['tag']['{html:title}'] = '关于FFS-Mini在线分享--'.SITE_NAM;
		$FFS['html']['tag']['{html:name}'] = SITE_NAM;
	    $FFS['html']['tag']['{html:keywords}'] = SITE_KEY;
	    $FFS['html']['tag']['{html:des}'] = SITE_DES;
		$FFS['html']['tag']['{html:tongji}'] = stripslashes(SITE_TONGJI);
		$FFS['html']['tag']['{html:ICP}'] = SITE_ICP;
		$FFS['html']['tag']['{html:reportKey}'] = "";
		$FFS['html']['tag']['{html:yourEmail}'] =!empty($_COOKIE['email']) ? $_COOKIE['email'] : '' ;
	}
	else{
		ERROR('错误页面','错误的页面访问');
	}
	}else{
		ERROR('运行提示','当前系统核心版本过低，请升级到C-111022');
	}
}
?>