<?php
if(RUN){
	if(VERSION('c',120226)){
	//http://u.fps88.com/?/so/str&page=page&type=type
	$val = explode('&page',VAL);
	$str = urldecode($val[0]);
	if(empty($_GET['page'])){$page="1";}else{$page=$_GET['page'];};
	if(empty($_GET['type'])){$type="";}else{$type=$_GET['type'];};
	if($str=='')ERROR('⊙﹏⊙','傻孩子，你啥都不输入叫我搜索什么？');
	if(empty($page)||$page<=0)$page=1;
	/*载入搜索配置*/
	include_once(MOP.'config.php');
	/*先检索数据*/
	$resb = FILE_SEARCH('name',$str,$type);
	$resb = STR_ARRSORT($resb,'down');

	/*分页并返回数据*/
	$resc=count($resb);
	
	if(SO_MAX!='0'){
		if($resc>SO_MAX){$resc=SO_MAX;};
		for($i=0;$i<$resc;$i++){
			$res[$i]=$resb[$i];
			}
		}
	else{
		$res=$resb;
		}
	$res=STR_PAGE($res,$page,SEARCH_NUM);
	if($res==false||$resc==0||$resb=="")
	{
			$FFS['html']['path']                   = MOP.'index.html'; 
			$FFS['html']['tag']['{html:title}']    = SITE_NAM.'-文件搜索';
			$FFS['html']['tag']['{html:keywords}'] = SITE_KEY;
			$FFS['html']['tag']['{html:des}']      = SITE_DES;
			$FFS['html']['tag']['{html:searchword}']      = $str;
			$FFS['html']['tag']['{html:tongji}'] = stripslashes(SITE_TONGJI);
			$FFS['html']['tag']['{html:ICP}'] = SITE_ICP;
			$FFS['html']['tag']['{html:reportKey}'] = "";
			$FFS['html']['tag']['{html:yourEmail}'] =!empty($_COOKIE['email']) ? $_COOKIE['email'] : '' ;
			$FFS['html']['tag']['{html:filter}']     = '<a href="?/so/'.$str.'&page=1&type=">全部</a> | <a href="?/so/'.$str.'&page=1&type=txt">TXT</a> | <a href="?/so/'.$str.'&page=1&type=doc">DOC</a> | <a href="?/so/'.$str.'&page=1&type=zip">ZIP</a> | <a href="?/so/'.$str.'&page=1&type=rar">RAR</a>  | <a href="?/so/'.$str.'&page=1&type=jpg">JPG</a> | <a href="?/so/'.$str.'&page=1&type=mp3">MP3</a> | <a href="?/so/'.$str.'&page=1&type=torrent">TORRENT</a> | <a href="?/so/'.$str.'&page=1&type=exe">EXE</a>';
			$FFS['html']['tag']['{html:resc}']     = '0';
			$FFS['html']['tag']['{html:search}']   = '';
			$FFS['html']['tag']['{html:pages}']  = '<option>暂无数据</option>';
			$FFS['html']['tag']['{html:nextpage}']  = '';
			$FFS['html']['tag']['{html:previouspage}']  = '';
		}
	else{
		/*处理分页*/
		$pages=
	    $page_li = '';
	    if($resc>SEARCH_NUM){
		    $i = 1;
		    $pages = ceil($resc/SEARCH_NUM);
		    while($i<=$pages){
			    $page_li=$page_li.'<option value="?/so/'.$str.'&page='.$i.'" '.( $_GET['page']==$i ? 'selected' : '').'>第['.$i.']页</option>';
			    $i++;
		    }
	    }else{
		    $pages=1;
	    }
		/*上一页 下一页*/	
	   		/*上一页 下一页*/
		if($pages==1){$page_li="<option>只有当前页</option>";$nextpage="";$previouspage="";}
		else{
			if(!empty($_GET['page'])){
				$currentpage=$_GET['page'];
				if($currentpage==1){
					$nextpage='<a href="?/so/'.$str.'&page=2">下一页</a>';
					$previouspage="";
					}
				elseif($currentpage==$pages){
					$nextpage='';
					$previouspage='<a href="?/so/'.$str.'&page='.($currentpage-1).'">上一页</a>';
					}
				else{
					$nextpage='<a href="?/so/'.$str.'&page='.($currentpage+1).'">下一页</a>';
					$previouspage='<a href="?/so/'.$str.'&page='.($currentpage-1).'">上一页</a>';					
					}
				}
			else{
					$nextpage='<a href="?/so/'.$str.'&page=2">下一页</a>';
					$previouspage="";				
				}
			}
	
	/*输出数据*/
	$sc = '';
	$resa=$res['data'];
	foreach($resa as $key){
		if(strlen($key['name'])>=50){
			$key['name']=CUT_STR($key['name'],40);
			}
		$sc=$sc.'
			 <p class="filename"><a href="'.URL.'?/file/view-'.$key['id'].'.html">'.str_replace(strtolower($str),'<span style=color:red>'.$str.'</span>',strtolower($key['name'])).'</a></p>
			 <p class="fileinfo">分享码：'.$key['id'].' |文件类型：'.$key['type'].' | 文件大小：'.STR_FILESIZE($key['size']).' | 上传时间：'.date('Y-m-d h:i',$key['time']).' | 下载次数：'.$key['down'].'</p>
			';	
	}
	$FFS['html']['path']                   = MOP.'index.html'; 
	$FFS['html']['tag']['{html:title}']    = SITE_NAM.'-文件搜索';
	$FFS['html']['tag']['{html:keywords}'] = SITE_KEY;
	$FFS['html']['tag']['{html:des}']      = SITE_DES;
	$FFS['html']['tag']['{html:searchword}']      = $str;
	$FFS['html']['tag']['{html:tongji}'] = stripslashes(SITE_TONGJI);
	$FFS['html']['tag']['{html:ICP}'] = SITE_ICP;
	$FFS['html']['tag']['{html:filter}']     = '<a href="?/so/'.$str.'&page=1&type=">全部</a> | <a href="?/so/'.$str.'&page=1&type=txt">TXT</a> | <a href="?/so/'.$str.'&page=1&type=doc">DOC</a> | <a href="?/so/'.$str.'&page=1&type=zip">ZIP</a> | <a href="?/so/'.$str.'&page=1&type=rar">RAR</a>  | <a href="?/so/'.$str.'&page=1&type=jpg">JPG</a> | <a href="?/so/'.$str.'&page=1&type=mp3">MP3</a> | <a href="?/so/'.$str.'&page=1&type=torrent">TORRENT</a> | <a href="?/so/'.$str.'&page=1&type=exe">EXE</a>';
	$FFS['html']['tag']['{html:resc}']     = $resc;
	$FFS['html']['tag']['{html:search}']   = $sc;
	$FFS['html']['tag']['{html:pages}']  = $page_li;
	$FFS['html']['tag']['{html:nextpage}']  = $nextpage;
	$FFS['html']['tag']['{html:previouspage}']  = $previouspage;
}
	}
	else{
		ERROR('运行提示','当前系统核心版本过低，请升级到C-120226');
		}
}
?>