<?php
if($_POST){
		/*处理上传*/
	    /*获取上传配置*/
		include('glob.php');
		include('app/upload/config.php');
		$info = FILE_UPLOAD();
		if($info['ok']){
			if(!empty($_POST['email'])){$smtpemailto=$_POST["email"];};
			$mailsubject=$info['name'];
			$mailbody='文件名：'.$info['name'].' | 分享码：'.$info['id'].' | 管理密码：'.$info['pw'].' | 文件类型：'.$info['type'].' | 文件大小：'.STR_FILESIZE($info['size']);
			$mailinfo = "";
			$jumpEmail=$_POST["jumpEmail"];
			if($jumpEmail=="no"){ $mailinfo=" | 邮件发送成功";};		  
			$str  = '文件名：'.$info['name'].'|分享码：'.$info['id'].'|管理密码:'.$info['pw'].'|下载地址：'.URL.'?/file/view-'.$info['id'].'.html';
			$str1 = '<object type="application/x-shockwave-flash" data="'.URL.'glob/copy/clipboard.swf" width="52" height="25" id="forLoadSwf" name="forLoadSwf" style="visibility: visible; "><param name="movie" value="'.URL.'glob/copy/clipboard.swf" /><param name="wmode" value="transparent"><param name="allowScriptAccess" value="always"><param name="flashvars" value="content='.$str.'&amp;uri='.URL.'glob/copy/flash_copy_btn.png"></object>';
			echo '上传完成-> <a  href="'.URL.'?/file/view-'.$info['id'].'.html" target="_blank" style="color:red;" >下载</a> '.(file_exists('app/info/plug.html') ? '<form action="'.URL.'?/file/edit-file.html" method="post" target="_blank" id="fileEditForm'.$info['id'].'"><input type="hidden" name="info_us" value="'.$info['id'].'" /><input type="hidden" name="info_pw" value="'.$info['pw'].'" /> <a  href="javascript:;" onclick="document.getElementById(\'fileEditForm'.$info['id'].'\').submit();" style="color:red;" >编辑</a></form>' : '' ).' 分享码：'.$info['id'].'|管理码:'.$info['pw'].$mailinfo.'<-'.$str1.FILE_QUICK_LINK($info['type'],$info['id'],$info['size']);
			if($jumpEmail=="no"){MAIL_SEND($smtpemailto,$mailsubject,$mailbody);}
            if(file_exists('app/manage/ico.html')&&$_POST['username']!=NULL&&$_POST['regtime']!=NULL){ //检测是否登录,如保存即发送保存链接
                @file_get_contents(URL.'?/manage/savefile|'.$info['id'].'|'.$_POST['username'].'|'.$_POST['regtime']);
            }
		}else{
			echo $info['info'];
		}
}


?>