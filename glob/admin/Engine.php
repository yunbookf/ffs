<?php
header('Content-Type:text/html;charset=utf-8'); 
if(RUN&&ADMIN){
	if(VERSION('c',110924)){
	if(ACT=='index'){
		/*读取配置，文件数量*/
		$files = FILE_READDB();
		/*创建页面数组*/
		$FFS['html']['path']=ROT.'glob/admin/system.html';
		$FFS['html']['tag']['{info:files}'] = '这里显示接入的页面内容';
        $FFS['html']['tag']['{html:title}'] = SITE_NAM;
	    $FFS['html']['tag']['{html:keywords}'] = SITE_KEY;
	    $FFS['html']['tag']['{html:des}']    = SITE_DES;
		$FFS['html']['tag']['{html:icp}']    = SITE_ICP;
		$FFS['html']['tag']['{html:qq}']    = SITE_QQ;
		$FFS['html']['tag']['{html:email}']    = SITE_EMAIL;
		$FFS['html']['tag']['{html:tel}']    = SITE_TEL;
		$FFS['html']['tag']['{html:tongji}'] = stripslashes(SITE_TONGJI);
		$FFS['html']['tag']['{site:upload_max_filesize}']   = ini_get('upload_max_filesize');
		$FFS['html']['tag']['{site:memory_limit}'] = ini_get('memory_limit');
		$FFS['html']['tag']['{site:post_max_size}']   = ini_get('post_max_size');
		$FFS['html']['tag']['{site:max_input_time}'] = ini_get('max_input_time');
		$FFS['html']['tag']['{site:VER1}'] = VER1;
		$FFS['html']['tag']['{site:VER2}'] = VER2;
		$FFS['html']['tag']['{site:disk}']   = STR_FILESIZE(disk_free_space(ROT));
		$FFS['html']['tag']['{site:files}']  = $files['count'];
		$FFS['html']['tag']['{site:save}']   = SAVE_DIR;
		$FFS['html']['tag']['{site:DEBUG}']   = DEBUG;
		$FFS['html']['tag']['{site:TASK_RUNTIME}']   = TASK_RUNTIME;
		$FFS['html']['tag']['{site:FONTFILE}']   = FONTFILE;
		
	}elseif(ACT=='siteSet'){
		/*创建robots.txt文件，并在首次提交到各大搜索引擎入口*/
		$FFS['html']['tag']['{site:sitemap}']=FILE_ROBOTS();
		
		/*创建页面数组*/
		$FFS['html']['path']=ROT.'glob/admin/siteSet.html';
		$FFS['html']['tag']['{info:files}'] = '这里显示接入的页面内容';
        $FFS['html']['tag']['{html:title}'] = SITE_NAM;
	    $FFS['html']['tag']['{html:keywords}'] = SITE_KEY;
	    $FFS['html']['tag']['{html:des}']    = SITE_DES;
		$FFS['html']['tag']['{html:icp}']    = SITE_ICP;
		$FFS['html']['tag']['{html:qq}']    = SITE_QQ;
		$FFS['html']['tag']['{html:email}']    = SITE_EMAIL;
		$FFS['html']['tag']['{html:tel}']    = SITE_TEL;
		$FFS['html']['tag']['{html:tongji}'] = stripslashes(SITE_TONGJI);
		$FFS['html']['tag']['{site:save}']   = SAVE_DIR;
		$FFS['html']['tag']['{site:DEBUG}']   = DEBUG;
		$FFS['html']['tag']['{site:TASK_RUNTIME}']   = TASK_RUNTIME;
		$FFS['html']['tag']['{site:SITEMAP_RUNTIME}']   = SITEMAP_RUNTIME;
		$FFS['html']['tag']['{site:FONTFILE}']   = FONTFILE;
		$FFS['html']['tag']['{site:SHIELDWORD}']   = SHIELDWORD;
		$FFS['html']['tag']['{site:PREFIX_AD}']   = PREFIX_AD;
		$FFS['html']['tag']['{site:SITE_CLOSE}']   = SITE_CLOSE;
		$FFS['html']['tag']['{site:SITE_CLOSE_REASON}']   = SITE_CLOSE_REASON;
		$FFS['html']['tag']['{site:FORSEARCH}']   = FORSEARCH;
		$FFS['html']['tag']['{site:FORSEARCHURL}']   ='http://so.fps88.com/forsearch.php?url='.stripslashes(URL).SAVE_DIR.'/forsearch.txt&forsearch='.FORSEARCH;
		if($_POST){
		$context = '<?php '."\n";
		unset($_POST['site_update']);
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
		if(file_put_contents('config.php',$context)){
			chmod('config.php',0777);
		    STR_EDITNOTICE('The_Action_Is_Ok!');
		}else{
			
		}
			}
	}elseif(ACT=='indexSet'){
				/*创建页面数组*/
		$FFS['html']['path']=ROT.'glob/admin/indexSet.html';
		include 'app/index/config.php';
		
		if($_POST){
		$context = '<?php '."\n";
		unset($_POST['index_update']);
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
		if(file_put_contents('app/index/config.php',$context)){
			chmod('app/index/config.php',0777);
		    STR_EDITNOTICE('The_Action_Is_Ok!');
		}else{
			
		}
			}
	}elseif(ACT=='adminSet'){
				/*创建页面数组*/
		$FFS['html']['path']=ROT.'glob/admin/adminSet.html';
		include 'glob/admin/config.php';

		if($_POST){
			if(STR_ENCRYPT(trim($_POST['OLD_PASSWORD']))!=ADMIN_PASSWORD){
				STR_EDITNOTICE('The_OldPassWord_Is_Wrong!');
			}
			else{
				if($_POST['ADMIN_PASSWORD']!=$_POST['ADMIN_PASSWORD_AGAIN']||$_POST['ADMIN_PASSWORD']==""){
					STR_EDITNOTICE('The_PassWord_Is_Not_Alike!');
					}
					else{
							$context = '<?php '."\n";
							$context=$context.'define(\'ADMIN_PASSWORD\',\''.STR_ENCRYPT(trim($_POST['ADMIN_PASSWORD'])).'\'); '."\n";
							$context=$context.' ?>';
							if(file_put_contents('glob/admin/config.php',$context)){
								chmod('glob/admin/config.php',0777);
								STR_EDITNOTICE('The_Action_Is_Ok!');
							}
					}
				}
			}
	}elseif(ACT=='mailConfig'){
				/*创建页面数组*/
		$FFS['html']['path']=ROT.'glob/admin/email/mailConfig.html';
		include 'glob/admin/email/mailConfig.php';
		$FFS['html']['tag']['{html:MAILWAY}'] = MAILWAY;
        $FFS['html']['tag']['{html:SMTPSERVER}'] = SMTPSERVER;
		$FFS['html']['tag']['{html:SMTPSERVERPORT}'] = SMTPSERVERPORT;
		$FFS['html']['tag']['{html:SMTPUSERMAIL}'] = SMTPUSERMAIL;
		$FFS['html']['tag']['{html:SMTPUSER}'] = SMTPUSER;
		$FFS['html']['tag']['{html:SMTPPASS}'] = SMTPPASS;
		$FFS['html']['tag']['{html:MAILSUBJECT}'] = MAILSUBJECT;
		$FFS['html']['tag']['{html:MAILTYPE}'] = MAILTYPE;
		$FFS['html']['tag']['{html:MAILBODY}'] = MAILBODY;
		if(!empty($_POST['mailTest'])){
		if($_POST['mailTest']){
			MAIL_TEST();
			}
		}
		if(!empty($_POST['mail_update'])){
		if($_POST['mail_update']){
		$context = '<?php '."\n";
		unset($_POST['mail_update']);
		unset($_POST['SMTPMAILTO']);
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
		if(file_put_contents('glob/admin/email/mailConfig.php',$context)){
			chmod('glob/admin/email/mailConfig.php',0777);
			STR_EDITNOTICE('The_Action_Is_Ok!');
		}else{
			
		}
		}
			}
	}elseif(ACT=='loginout'){
		unset($_SESSION['login']);
		header('Location:login.php');
	}elseif(ACT=='dbUpdate'){
		STR_EDITNOTICE('The_Action_Is_Ok!');
		FILE_MAKEDB();
	}elseif(ACT=='sitemapUpdate'){
		STR_EDITNOTICE('The_Sitemap_Is_Create!');
		FILE_SITEMAP();
	}elseif(ACT=='reportList'){
	    $FFS['html']['path']=ROT.'glob/admin/reportList.html';	
		/*更新举报列表*/
		if(!empty($_POST['updateReportList'])){
			FILE_REPORT_LIST_UPDATE();
			STR_EDITNOTICE('The_Action_Is_Ok!');
			};
		/*获取数据*/
		$res=array();
		$res = FILE_REPORT_LIST();
		/*给数据排序*/
		$res = STR_ARRSORT($res,'time');
		/*获取分页数据*/
		if(empty($_GET['page'])){$page=1;}else{$page = $_GET['page'];}
		/*分页*/
		$resc=count($res);
	   $res=STR_PAGE($res,$page,50);
	    if($res==false||$resc==0){
				$FFS['html']['tag']['{html:counts}']   = 0;
				$FFS['html']['tag']['{html:filelist}']   = '<td></td><td colspan="4">抱歉，暂无任何举报！</td>';
				$FFS['html']['tag']['{html:pages}']  = '<option>只有当前页</option>';
				$FFS['html']['tag']['{html:nextpage}']  = '';
				$FFS['html']['tag']['{html:previouspage}']  = '';
				$FFS['html']['tag']['{html:data}']  = !empty($_GET['data']) ?$_GET['data'] : '';		
			}
		else{
		/*处理分页*/
	    $page_li = '';
	    if($resc>50){
		    $i = 1;
		    $pages = ceil($resc/50);
		    while($i<=$pages){
			    $page_li=$page_li.'<option value="admin.php?mode=admin&action=reportList&filter='.$filter.'&data='.$data.'&page='.$i.'" '.( $_GET['page']==$i ? 'selected' : '').'>第['.$i.']页</option>';
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
					$nextpage='<a href="admin.php?mode=admin&action=reportList&filter='.$filter.'&data='.$data.'&page=2">下一页</a>';
					$previouspage="";
					}
				elseif($currentpage==$pages){
					$nextpage='';
					$previouspage='<a href="admin.php?mode=admin&action=reportList&filter='.$filter.'&data='.$data.'&page='.($currentpage-1).'">上一页</a>';
					}
				else{
					$nextpage='<a href="admin.php?mode=admin&action=reportList&filter='.$filter.'&data='.$data.'&page='.($currentpage+1).'">下一页</a>';
					$previouspage='<a href="admin.php?mode=admin&action=reportList&filter='.$filter.'&data='.$data.'&page='.($currentpage-1).'">上一页</a>';					
					}
				}
			else{
					$nextpage='<a href="admin.php?mode=admin&action=reportList&filter='.$filter.'&data='.$data.'&page=2">下一页</a>';
					$previouspage="";				
				}
			}
		
		$filelist="";
		if($resc>0){
		foreach($res['data'] as $re){ 
			$filelist=$filelist.'<tr>
          <td><input type="checkbox" name="select[]" class="checkbox" value="'.$re['id'].'" /></td>
          <td><a href="admin.php?mode=admin&action=fileEdit&editid='.$re['id'].'" target="_blank" title="预览此文件：'.$re['id'].'">'.$re['id'].'</a></td>
		  <td>'.$re['email'].'</td>
          <td>'.$re['content'].'</td>
          <td>'.date('Y-m-d H:i',$re['time']).'</td>
        </tr>
';
			}
		}
		$FFS['html']['tag']['{html:data}']  = !empty($_GET['data']) ? $_GET['data'] : '';	
		$FFS['html']['tag']['{html:counts}']   = $resc;
		$FFS['html']['tag']['{html:filelist}']   = $filelist;
		$FFS['html']['tag']['{html:pages}']  = $page_li;
		$FFS['html']['tag']['{html:nextpage}']  = $nextpage;
		$FFS['html']['tag']['{html:previouspage}']  = $previouspage;
		
		if(!empty($_POST['files_del'])){
		if($_POST['files_del']){
			$select=$_POST['select'];
			if($select!="")
			{
				$del_num=count($select);
				for($i=0;$i<=$del_num;$i++)
				{
					FILE_DELETE($select[$i]);
					FILE_REPORT_DELETE($select[$i]);
					}
				FILE_MAKEDB();
				FILE_REPORT_LIST_UPDATE();
				STR_EDITNOTICE('Delete_Files_Is_Ok!');
			}
			}
		}
		if(!empty($_POST['report_del'])){
		if($_POST['report_del']){
			$select=$_POST['select'];
			if($select!="")
			{
				$del_num=count($select);
				for($i=0;$i<=$del_num;$i++)
				{
					FILE_REPORT_DELETE($select[$i]);
					}
				FILE_REPORT_LIST_UPDATE();
				STR_EDITNOTICE('Delete_Reports_Is_Ok!');
			}
			}
		}
		}
	}elseif(ACT=='fileEdit'){
				/*创建页面数组*/
		$FFS['html']['path']=ROT.'glob/admin/fileEdit.html';
		if(!empty($_GET['editid'])){
			$id=$_GET['editid'];
			}
		$file=FILE_REINFO($id);
		$FFS['html']['tag']['{html:FILENAME}'] = $file['name'];
		$FFS['html']['tag']['{html:FILEKEY}'] = $id;
		$FFS['html']['tag']['{html:FILEPASS}'] = $file['pw'];
		$FFS['html']['tag']['{html:FILETYPE}'] = $file['type'];
		$FFS['html']['tag']['{html:FILESIZE}'] = STR_FILESIZE($file['size']);
		$FFS['html']['tag']['{html:FILETIME}'] = date('Y-m-d H:i:s',$file['time']);
		$FFS['html']['tag']['{html:FILEIP}'] = $file['ip'];
		$FFS['html']['tag']['{html:FILELAST}'] = date('Y-m-d H:i:s',$file['last']);
		$FFS['html']['tag']['{html:FILEDOWN}'] =$file['down'];
		$FFS['html']['tag']['{html:FILEINFO}'] = 	$file['info'];
			
			/*获取编辑数据*/
			if(!empty($_POST['thisfile_update'])){
				$file=FILE_REINFO($_POST['FILEKEY']);
				$file['id']=$_POST['FILEKEY'];
				$file['name']=$_POST['FILENAME'];
				$file['pw']=$_POST['FILEPASS'];
				$file['down']=$_POST['FILEDOWN'];
				$file['info']=$_POST['FILEINFO'];
				FILE_MKINFO($file);
				STR_EDITNOTICE('Edit_File_Is_Ok!');
				}
		}elseif(ACT=='fileList'){
	    $FFS['html']['path']=ROT.'glob/admin/fileList.html';	
		 /*先检索数据*/  
		if(!empty($_POST['data'])){
			header("Location:admin.php?mode=admin&action=fileList&filter=$_POST[filter]&data=$_POST[data]");
			};
		if(!empty($_GET['data'])){
			$data=trim($_GET['data']);$filter=trim($_GET['filter']);
			}
		else{
			$data="";$filter="";$searchword="";
			}
		
		/*显示筛选*/
		$FFS['html']['tag']['{html:filter}']   = '<option value="searchword" '.($filter=='searchword' ? 'selected="selected"' : '').'>关键词</option><option value="fid" '.($filter=='fid' ? 'selected="selected"' : '').' >分享码</option><option value="day" '.($filter=='day' ? 'selected="selected"' : '').'>最后下载距当前大于（天）</option><option value="down" '.($filter=='down' ? 'selected="selected"' : '').'>下载次数小于（次）</option>';
		
	    $res = FILE_CLEAR($filter,$data);
		/*总个数*/
		$resc=count($res);
		/*给数据排序*/
		$res = STR_ARRSORT($res,'time');
		/*获取分页数据*/
		if(empty($_GET['page'])){$page=1;}else{$page = $_GET['page'];}
		
		/*分页并返回数据*/
	   $res=STR_PAGE($res,$page,50);
	    if($res==false||$resc==0){
				$FFS['html']['tag']['{html:counts}']   = 0;
				$FFS['html']['tag']['{html:filelist}']   = '<td></td><td colspan="9">抱歉，暂无任何数据！</td>';
				$FFS['html']['tag']['{html:pages}']  = '';
				$FFS['html']['tag']['{html:pages}']  = '<option>暂无任何数据</option>';
				$FFS['html']['tag']['{html:nextpage}']  = '';
				$FFS['html']['tag']['{html:previouspage}']  = '';	
				$FFS['html']['tag']['{html:data}']  = !empty($_GET['data']) ?$_GET['data'] : '';	
			}
		else{
		/*处理分页*/
	    $page_li = '';
	    if($resc>50){
		    $i = 1;
		    $pages = ceil($resc/50);
		    while($i<=$pages){
			    $page_li=$page_li.'<option value="admin.php?mode=admin&action=fileList&filter='.$filter.'&data='.$data.'&page='.$i.'" '.( !empty($_GET['page']) ? ($_GET['page']==$i ? 'selected' : '') : '').' >第['.$i.']页</option>';
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
					$nextpage='<a href="admin.php?mode=admin&action=fileList&filter='.$filter.'&data='.$data.'&page=2">下一页</a>';
					$previouspage="";
					}
				elseif($currentpage==$pages){
					$nextpage='';
					$previouspage='<a href="admin.php?mode=admin&action=fileList&filter='.$filter.'&data='.$data.'&page='.($currentpage-1).'">上一页</a>';
					}
				else{
					$nextpage='<a href="admin.php?mode=admin&action=fileList&filter='.$filter.'&data='.$data.'&page='.($currentpage+1).'">下一页</a>';
					$previouspage='<a href="admin.php?mode=admin&action=fileList&filter='.$filter.'&data='.$data.'&page='.($currentpage-1).'">上一页</a>';					
					}
				}
			else{
					$nextpage='<a href="admin.php?mode=admin&action=fileList&filter='.$filter.'&data='.$data.'&page=2">下一页</a>';
					$previouspage="";				
				}
			}
		
		/*文件列表*/
		$filelist="";
		foreach($res['data'] as $re){
			$filelist=$filelist.'<tr>
          <td><input type="checkbox" name="select[]" class="checkbox" value="'.$re['id'].'" /></td>
          <td><a href="index.php?/file/view-'.$re['id'].'.html" target="_blank" title="前台预览此文件：'.$re['name'].'">'.$re['id'].'</a></td>
          <td><a href="admin.php?mode=admin&action=fileEdit&editid='.$re['id'].'" target="_blank" title="查看编辑此文件：'.$re['name'].'">'.$re['name'].'</a></td>
		  <td>'.$re['pw'].'</td>
          <td>'.$re['type'].'</td>
          <td>'.STR_FILESIZE($re['size']).'</td>
          <td>'.date('Y-m-d H:i',$re['time']).'</td>
		  <td>'.date('Y-m-d H:i',$re['last']).'</td>
          <td>'.$re['down'].'次</td>
        </tr>
';
			}
		$FFS['html']['tag']['{html:data}']  = !empty($_GET['data']) ?$_GET['data'] : '';	
		$FFS['html']['tag']['{html:counts}']   = $resc;
		$FFS['html']['tag']['{html:filelist}']   = $filelist;
		$FFS['html']['tag']['{html:pages}']  = $page_li;
		$FFS['html']['tag']['{html:nextpage}']  = $nextpage;
		$FFS['html']['tag']['{html:previouspage}']  = $previouspage;
		if(!empty($_POST['files_del'])){
		if($_POST['files_del']){
			$select=$_POST['select'];
			if($select!="")
			{
				$del_num=count($select);
				for($i=0;$i<=$del_num;$i++)
				{
					FILE_DELETE($select[$i]);
					}
				STR_EDITNOTICE('Delete_Files_Is_Ok!');
				FILE_MAKEDB();
			}
			}
		}
		}
	}
	}
	else{
		ERROR('运行提示','当前系统核心版本过低，请升级到C-110924');
		}
		
}

?>