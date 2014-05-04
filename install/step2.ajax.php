<?php
function deaddslashes(&$str) {
	if (is_array($str))
		foreach ($str as $key => $v)
			deaddslashes($str[$key]);
	elseif (is_string($str))
		$str = stripslashes($str);
}

if (get_magic_quotes_gpc())	{
	deaddslashes($_GET);
	deaddslashes($_POST);
	deaddslashes($_COOKIE);
	deaddslashes($_REQUEST);
}

function folderCanWrite($path) {
	$fPath=$path.'/testtestfiliant.txt';
	$dPath=$path.'/testtestfiliantfolder';
	if($fp=@fopen($fPath,'w')) {
		if(@is_writable($fPath)) {
			if(!@fwrite($fp,'test'))
				return false;
		} else
			return false;
		@fclose($fp);
		@unlink($fPath);
		//folder
		if(@mkdir($dPath,0777)) {
			if(!@rmdir($dPath))
				return false;
			$mydir = dir($path);
			while($file = $mydir->read()) {
				if((is_dir($path.'/'.$file)) && ($file!='.') && ($file!='..')) {
					if(@rename($path.'/'.$file, $path.'/testfiliantrename.maiyun')) {
						rename($path.'/testfiliantrename.maiyun', $path.'/'.$file);
						$mydir->close(); 
						return true;
					} else {
						$mydir->close(); 
						return false;
					}
				}
			}
			$mydir->close();
			return true;
		} else
			return false;
	} else
		return false;
}

if(!is_file('install.lock')) {
	if($_POST['ac'] == 'JSON Support') {
		if(function_exists('json_encode')) {
			echo json_encode(array(
				'result' => '1',
				'msg' => 'OK<br />'
			));
		} else {
			echo '{"result":"0","msg":"NO<br />不支持 json_encode 函数，安装向导终止。"}';
		}
	} else if($_POST['ac'] == 'PHP_VERSION') {
		if(version_compare(PHP_VERSION,'5.2.1','>=')) {
			echo json_encode(array(
				'result' => '1',
				'msg' => PHP_VERSION . '...OK<br />'
			));
		} else {
			echo json_encode(array(
				'result' => '0',
				'msg' => PHP_VERSION . '...NO<br />PHP 版本太低，至少要 5.2，安装向导终止。'
			));
		}
	} else if($_POST['ac'] == 'mysql_connect Support') {
		if(function_exists('mysql_connect')) {
			echo json_encode(array('result' => '1','msg' => 'OK<br />'));
		} else {
			echo json_encode(array('result' => '0','msg' => 'NO<br />必须开启 MySQL 扩展支持才可继续，安装向导终止。'));
		}
	} else if($_POST['ac'] == 'Write test of root folder') {
		if(folderCanWrite('../')) {
			echo json_encode(array('result' => '1','msg' => 'OK<br />'));
		} else {
			echo json_encode(array('result' => '0','msg' => 'NO<br />请将根目录权限设置为 0777，安装向导终止。'));
		}
	} else if($_POST['ac'] == 'Write test of "session" folder') {
		if(folderCanWrite('../session')) {
			echo json_encode(array('result' => '1','msg' => 'OK<br />'));
		} else {
			echo json_encode(array('result' => '0','msg' => 'NO<br />请将 session 目录权限设置为 0777，安装向导终止。'));
		}
	} else if($_POST['ac'] == 'Write test of "system" folder') {
		if(folderCanWrite('../system')) {
			echo json_encode(array('result' => '1','msg' => 'OK<br />'));
		} else {
			echo json_encode(array('result' => '0','msg' => 'NO<br />请将 system 目录权限设置为 0777，安装向导终止。'));
		}
	} else if($_POST['ac'] == 'Write test of "system/file" folder') {
		if(folderCanWrite('../system/file')) {
			echo json_encode(array('result' => '1','msg' => 'OK<br />'));
		} else {
			echo json_encode(array('result' => '0','msg' => 'NO<br />请将 system/file 目录权限设置为 0777，安装向导终止。'));
		}
	} else if($_POST['ac'] == 'Write test of "glob/admin" folder') {
		if(folderCanWrite('../glob/admin')) {
			echo json_encode(array('result' => '1','msg' => 'OK<br />'));
		} else {
			echo json_encode(array('result' => '0','msg' => 'NO<br />请将 glob/admin 目录权限设置为 0777，安装向导终止。'));
		}
	} else if($_POST['ac'] == 'Write test of "glob/admin/email" folder') {
		if(folderCanWrite('../glob/admin/email')) {
			echo json_encode(array('result' => '1','msg' => 'OK<br />'));
		} else {
			echo json_encode(array('result' => '0','msg' => 'NO<br />请将 glob/admin/email 目录权限设置为 0777，安装向导终止。'));
		}
	} else if($_POST['ac'] == 'Write test of "app/index" folder') {
		if(folderCanWrite('../app/index')) {
			echo json_encode(array('result' => '1','msg' => 'OK<br />'));
		} else {
			echo json_encode(array('result' => '0','msg' => 'NO<br />请将 app/index 目录权限设置为 0777，安装向导终止。'));
		}
	} else if($_POST['ac'] == 'Write test of "app/file" folder') {
		if(folderCanWrite('../app/file')) {
			echo json_encode(array('result' => '1','msg' => 'OK<br />'));
		} else {
			echo json_encode(array('result' => '0','msg' => 'NO<br />请将 app/file 目录权限设置为 0777，安装向导终止。'));
		}
	} else if($_POST['ac'] == 'Write test of "app/file/sync" folder') {
		if(folderCanWrite('../app/file/sync')) {
			echo json_encode(array('result' => '1','msg' => 'OK<br />'));
		} else {
			echo json_encode(array('result' => '0','msg' => 'NO<br />请将 app/file/sync 目录权限设置为 0777，安装向导终止。'));
		}
	} else if($_POST['ac'] == 'Write test of "app/upload" folder') {
		if(folderCanWrite('../app/upload')) {
			echo json_encode(array('result' => '1','msg' => 'OK<br />'));
		} else {
			echo json_encode(array('result' => '0','msg' => 'NO<br />请将 app/upload 目录权限设置为 0777，安装向导终止。'));
		}
	}
}

