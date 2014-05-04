<?php
$BASE = 'http://localhost/ffsK/';
$THISURL='http://localhost/ffsb';
$KDIR = 'system2';
$quest = $_SERVER['QUERY_STRING'];
$act = $quest;
$val = '';
switch ($act) {
        //同步配置
    case 'sync':
        if(!file_exists($KDIR)){
            mkdir($KDIR,0777);chmod($KDIR,0777);
            mkdir($KDIR.'/data',0777);chmod($KDIR.'/data',0777);
            mkdir($KDIR.'/file',0777);chmod($KDIR.'/file',0777);
            mkdir($KDIR.'/lock',0777);chmod($KDIR.'/lock',0777);
        }
		$url=$BASE . '?/file/sync';
		$c=file_get_contents($url);
		if($c!=false){
			file_put_contents('config.php', $c);
			chmod('config.php',0777);
			if(file_exists('config.php')){
					echo 'A';
				}
				else{
					echo  'B';
				}
			}
        break;
        //清空数据
    case 'clean':
        if (!ldn::dir_delete($KDIR . 'file/')){
            mkdir($KDIR.'/file',0777);die('false');
        }            
        if (!ldn::dir_delete($KDIR . 'data/')){
            mkdir($KDIR.'/data',0777);
        }
            die('false');
        echo 'true';
        break;
      
}

 //下载数据
if(strstr($act,'file')){ 
		include('config.php');
        $nowtime = mktime();
		$gettime = substr($act,4,10);
		$id= substr($act,14,7);
		$baseUrl=$BASE.'d.php?'.$act;
		$fromFile=array();
		$fromFile['id']=$id;
		ldn::siteBan($BASE,$fromFile);
        $bpath = ldn::mk_path($KDIR . '/data/', $id, true) . $id . '.dbs';
        $lpath = $KDIR . '/lock/' . $id . '.lok';
        $fpath = ldn::mk_path($KDIR . '/file/', $id , true) . $id . '.dat';
		$maxtime = $nowtime+FILE_DMT*24*3600;	
		//如果没有超过时间限制
		if($maxtime>$gettime && ($nowtime-$gettime)<$maxtime){
			if (!file_exists($bpath)) {
				//该文件不存在于LDN节点
				//建立源站数据连接
				$fileinfo = file_get_contents($BASE . '?/file/query-' . $id . '.query' );
				//建立记录
				file_put_contents($bpath, $fileinfo);
				file_put_contents($lpath, '1');
				$data='baseUrl='.$baseUrl.'&fpath='.$fpath.'&lpath='.$lpath.'&bpath='.$bpath;
				
 				ldn::cPOST($THISURL.'/down.php',$data);
				//header
				header('location:'.$baseUrl);
				exit;
				
				//file_get_contents('down.php?baseUrl='.$baseUrl.'&fpath='.$fpath.'&lpath='.$lpath.'&bpath='.$bpath);
			} elseif (file_exists($lpath)) {
				//记录存在，但是还没有同步完成.
				//直接输出头部信息
				header('location:'.$baseUrl);
				exit;
			} else {
				//已经同步好的，直接输出。
					
					$info = unserialize(file_get_contents($bpath));
					ldn::file_down($fpath, $info['name'], $info['type'], $info['size'], FILE_DSP, PREFIX_AD);
					//下载完成发送一个统计信息
					file_get_contents($BASE . '?/file/update-' . $id. '.update');
					
			}					
		}else{
				//超过时间限制则返回本站的下载地址，自动跳转到错误页面。
				header('location:'.$baseUrl);
			}
	}

class ldn
{
    static function mk_path($header, $id, $mod)
    {
        $time = self::de_id($id);
        if (empty($header))return false;
        $y = date('Y', $time);
        $m = date('m', $time);
        if ($mod) {
            if (!file_exists($header . "$y/")) {
                mkdir($header . "$y/", 0777);
                chmod($header . "$y/", 0777);
            }
            if (!file_exists($header . "$y/$m/")) {
                mkdir($header . "$y/$m/", 0777);
                chmod($header . "$y/$m/", 0777);
            }
        }
        $data_path = $header . "$y/$m/";
        return $data_path;
    }
    static function de_id($id)
    {
        if ($id == '')return false;
        $id = substr($id, 1);
        $num = '';
        for ($i = 0; $i <= strlen($id) - 1; $i++) {
            $a = substr($id, $i, 1);
            if (ord($a) <= 57) {
                $num = ($num + ((ord($a) - 48) * self::ma_bcpow(36, strlen($id) - $i - 1)));
            } else {
                $num = ($num + ((ord($a) - 55) * self::ma_bcpow(36, strlen($id) - $i - 1)));
            }
        }
        if ((strlen($num)) != 10)return false;
        return $num;
    }
    static function ma_bcpow($x, $y)
    {
        $a = 1;
        for ($row = 1; $row <= $y; $row++) {
            $a = $a * $x;
        }
        return $a;
    }
    static function ck_config($url)
    {
        if (file_exists('config.php')) {
            include_once ('config.php');
            return true;
        } else {
            file_put_contents('config.php', $url . '?file/sync');
        }
    }
    static function file_down($filepath, $filename, $filetype, $filesize, $speed, $ad,
        $disposition = false)
    {
        $filepath = $filepath;
        $downname = iconv("UTF-8", "GBK", $ad . $filename . '.' . $filetype);
        header('Cache-control: private');
        header('Content-type: ' . self::mime($filetype));
        header('Content-Length: ' . $filesize);
        if ($disposition) {
            header('Content-Disposition: inline; filename="' . $downname . '"');
        } else {
            header('Content-Disposition: attachment; filename="' . $downname . '"');
        }
        if (!$fp = fopen($filepath, 'rb')) {
            exit;
        }
        set_time_limit(86400);
        if ($speed == 0) {
            readfile($filepath);
        } else {
            $times = intval(($speed * 1024) / 8192) + 1;
            while (!feof($fp)) {
                $i = 0;
                while ($i < $times) {
                    echo fread($fp, 8192);
                    $i = $i + 1;
                }
                unset($i);
                ob_flush();
                flush();
                sleep(1);
            }
        }
		fclose($fp);
    }
    static function file_ok($save, $id)
    {
        //建立数据文件路径以及记录文件路径
        $fpath = self::mk_path($save . 'file/', $id, false) . '.dat';
        $dpath = self::mk_path($save . 'data/', $id, false) . '.dbs';
    }
    static function dir_delete($die)
    {

        $dh = opendir($dir);
        while ($file = readdir($dh)) {
            if ($file != "." && $file != "..") {
                $fullpath = $dir . "/" . $file;
                if (!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    self::dir_delete($fullpath);
                }
            }
        }

        closedir($dh);

        if (rmdir($dir)) {
            return true;
        } else {
            return false;
        }

    }
    static function mime($type)
    {
        switch ($type) {
            case 'avi':
                $mime = 'video/x-msvideo';
                break;
            case 'bmp':
                $mime = 'image/bmp';
                break;
            case 'css':
                $mime = 'text/css';
                break;
            case 'dll':
                $mime = 'application/x-msdownload';
                break;
            case 'doc':
                $mime = 'application/msword';
                break;
            case 'dot':
                $mime = 'application/msword';
                break;
            case 'gif':
                $mime = 'image/gif';
                break;
            case 'gz':
                $mime = 'application/x-gzip';
                break;
            case 'htm':
                $mime = 'text/html';
                break;
            case 'html':
                $mime = 'text/html';
                break;
            case 'ico':
                $mime = 'image/x-icon';
                break;
            case 'jpeg':
                $mime = 'image/jpeg';
                break;
            case 'jpg':
                $mime = 'image/jpeg';
                break;
            case 'gif':
                $mime = 'image/gif';
                break;
            case 'png':
                $mime = 'image/png';
                break;
            case 'js':
                $mime = 'application/x-javascript';
                break;
            case 'mdb':
                $mime = 'application/x-msaccess';
                break;
            case 'mid':
                $mime = 'audio/mid';
                break;
            case 'mp3':
                $mime = 'audio/mpeg';
                break;
            case 'mpeg':
                $mime = 'video/mpeg';
                break;
            case 'mvb':
                $mime = 'application/x-msmediaview';
                break;
            case 'pps':
                $mime = 'application/vnd.ms-powerpoint';
                break;
            case 'ppt':
                $mime = 'application/vnd.ms-powerpoint';
                break;
            case 'txt':
                $mime = 'text/plain';
                break;
            case 'wav':
                $mime = 'audio/x-wav';
                break;
            case 'xls':
                $mime = 'application/vnd.ms-excel';
                break;
            case 'zip':
                $mime = 'application/zip';
                break;
            case 'rar':
                $mime = 'application/x-rar-compressed';
                break;
            case 'swf':
                $mime = 'application/x-shockwave-flash';
                break;
            case '7z':
                $mime = 'application/x-7z-compressed';
                break;
            default:
                $mime = 'application/object-stream';
                break;
        }
        return $mime;
    }
	static function  cPOST($url,$data){
			$ch = curl_init($url);
			$curl_opt = array(CURLOPT_POSTFIELDS => $data);
			curl_setopt_array($ch, $curl_opt);
			curl_setopt($ch, CURLOPT_TIMEOUT, 1);
			curl_exec($ch);
			curl_close($ch);
		}
	static function siteBan($host,$file){
			if(trim(SITEBAN_LIST)!=""&&$_SERVER['HTTP_REFERER']!=""){
				$urlarray=parse_url($_SERVER['HTTP_REFERER']);
				$url=$urlarray['host'];
				$sitelists=trim(SITEBAN_LIST);
				if(SITELIST_BAN==0){
						if(!strstr($sitelists,$url)){
							ERROR('访问出错','此网站不允许直接连接本站文件！<br />点此重新提取文件：<a href="'.$host.'?/file/view-'.$file['id'].'.html">'.$host.'?/file/view-'.$file['id'].'.html</a>');
							}
					}
				else{
						if(strstr($sitelists,$url)){
							ERROR('访问出错','此网站不允许直接连接本站文件！<br />点此重新提取文件：<a href="'.$host.'?/file/view-'.$file['id'].'.html">'.$host.'?/file/view-'.$file['id'].'.html</a>');
							}
				   }
				}
			}
}
?>