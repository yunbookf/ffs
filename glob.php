<?php
header("content-Type: text/html; charset=utf-8");
/*载入配置*/
include 'config.php';
/*——————————定义常量——————————————
ROT:系统目录……URL:站点地址……SYS:安全目录……RUN:安全常量
*/
$www_dir = pathinfo($_SERVER['SCRIPT_NAME']);
$www_url = str_replace("\\", '', $_SERVER['HTTP_HOST'] . $www_dir['dirname']);
define('ROT', str_replace('\\', '/', dirname(__file__)) . '/');
define('URL', 'http://' . str_replace('//', '/', $www_url . '/'));
define('SYS', ROT . SAVE_DIR . '/');
define('RUN', true);
define('VER1', 'C-140430');
define('VER2', 'M-140430');
/*————————————DEBUG——————————————*/
/*task.php*/
include_once 'task.php';
/*建立全局变量*/
$FFS = array();
/*
————————————文件操作函数定义————————————
FILE_UPLOAD     :上传处理，固定模式的上传处理函数，自动处理文件，返回执行结果。
FILE_MKPATH     :生成路径，有两种模式，区别在于是否在获取不到路径的时候自动生成。
FILE_IDPATH     :解析路径，传入分享码，头部路径，返回一个由分享码解析的路径，其值不受安全设置的影响。
FILE_DELETE     :删除文件，将会把文件记录和文件数据同时删除，返回布尔值。
FILE_MKINFO     :生成记录，传入一个带有ID的数组，生成记录文件。
FILE_REINFO     :访问记录，传入ID作为索引，查找目标记录，并返回解压的数组。
FILE_OUTPUT     :输出内容，传入文件数组，和速度限制值，输出该文件的内容。自动记录下载次数，最后下载的时间。
FILE_MAKEDB     :创建虚拟数据库，创建一个虚拟数据库，用于大规模数据信息查询。
FILE_READDB     :读取虚拟数据库，把虚拟数据库的内容输出，可以指定记录起始位置和结束位置，定向读取。
FILE_SEARCH     :搜索虚拟数据库，传入一个要搜索的字段，以及关键词，然后返回结果，没有结果返回false。
FILE_CLEAR      ：传入清理规则，列出可以清理的文件。
FILE_REPORT    :传入文件提取码和理由，返回是否已经受过举报。
FILE_REPORT_LIST_UPDATE    :更新举报列表。
FILE_REPORT_LIST :生成举报列表
FILE_REPORT_DELETE :删除举报
FILE_CREATE_IMG      :创建文件信息图片
FILE_SITEMAP           :创建网站地图
FILE_ROBOTS          :创建robots.txt文件
FILE_FORSEARCH      :创建用于搜索引擎的信息
FILE_QUICK_LINK      :根据文件类型和已安装扩展输出快捷链接
FILE_SYNC				   :传入一个字符串，分割成目录形式的配置文件
FILE_SYNC_TRY		   :传入一个字符串，生成配置文件并测试。

————————————字串操作函数定义————————————
STR_BCPOW       :传入两个数值，返回x的y次方
STR_IDENCODE    :生成ID，需要传入一个UNIX时间戳，然后返回7位编码。
STR_IDDECODE    :解压ID，传入编码，返回一个UNIX时间戳。
STR_FILENAME    :传入带有后缀的文件名，除掉后缀后返回。
STR_FILETYPE    :传入带有后缀的文件名，只将后缀返回。
STR_FILEMIME    :传入带有后缀的文件名，根据后缀返回文件的MIME类型。
STR_FILESIZE    :传入文件字节数，返回格式化的文件大小。
STR_EDITNOTICE  :传入字符串，后台编辑成功显示结果。
CUT_STR         :字符串截取，传入字符串和所要截取的长度。
STR_ENCRYPT     :加密字符串
STR_ARRSORT     :传入需要排序的数组，指定字段，进行排序，返回结果数组。
STR_TIME		:按时间倒序排列	
STR_DOWN		:按文件下载次数倒序排列
STR_REQUEST_URI :获取当前URL
STR_CUT_KEY     :根据当前URL获取KEY
STR_SITEBAN     :域名黑白名单
————————————页面引擎函数定义————————————
HTML_LOAD       :载入模板
HTML_CONTENT    :输入标签数组
HTML_PUT        :输出页面内容
————————————系统控制函数定义————————————
ERROR           :传入两个参数，一个是标题，一个是详细信息。
VERSION         :传入对比类型，需求版本号，返回结果。
SITE_CLOSE      :站点关闭
————————————邮件函数定义————————————
MAIL_TEST  :作为测试当前邮件配置用
MAIL_SEND  :发送邮件
MAIL_SEND_VIP  :发送会员邮件
*/
/*————————————文件操作函数开始————————————*/
function FILE_UPLOAD()
{
    /*检测屏蔽关键词*/
    $shieldword = explode(',', SHIELDWORD);
    foreach ($shieldword as $shieldwordA)
    {
        if (stristr(STR_FILENAME($_FILES['file']['name']), $shieldwordA))
        {
            $re['info'] = '上传失败：请勿上传非法文件!';
            $re['ok'] = false;
            return $re;
        }
    }
    if (UPLOAD_TYPE != '*.*')
    {
        $uptype = strtolower(STR_FILETYPE($_FILES['file']['name']));
        if (!stristr(UPLOAD_TYPE, $uptype))
        {
            $re['info'] = '上传失败：请勿上传本站不允许格式的文件!';
            $re['ok'] = false;
            return $re;
        }
    }
    /*上传超时 */
    if (empty($_FILES['file']))
    {
        $re['info'] = '上传失败：超时!';
        $re['ok'] = false;
        return $re;
    }
    /* 文件大小不能为0 */
    if ($_FILES['file']['size'] == 0)
    {
        $re['info'] = '上传失败：文件大小为0字节';
        $re['ok'] = false;
        return $re;
    }
    if ($_FILES['file']['size'] > UPLOAD_SIZE*1024*1024)
    {
        $re['info'] = '上传失败：文件大小不能大于'.UPLOAD_SIZE.'MB';
        $re['ok'] = false;
        return $re;
    }
    $re['name'] = STR_FILENAME($_FILES['file']['name']);
    $re['type'] = strtolower(STR_FILETYPE($_FILES['file']['name']));
    $re['mime'] = STR_FILEMIME($_FILES['file']['name']);
    $re['size'] = $_FILES['file']['size'];
    $re['time'] = time();
    $re['last'] = time();
    $re['down'] = 0;
    $re['info'] = 'none';
    $re['ip'] = $_SERVER['REMOTE_ADDR'];
    $re['id'] = STR_IDENCODE($re['time']);
    $re['pw'] = rand(100000, 999999);
    $sp = FILE_MKPATH($re['id'], true) . "{$re['id']}.dat";
    if (move_uploaded_file($_FILES['file']['tmp_name'], $sp))
    {
        FILE_MKINFO($re);
        $re['ok'] = true;
        return $re;
    } else
    {
        $re['ok'] = false;
        $re['info'] = '上传失败，文件转移过程中出错。';
        return $re;
    }
}
function FILE_MKPATH($id, $mod = false)
{
    $time = STR_IDDECODE($id);
    $y = date('Y', $time);
    $m = date('m', $time);
    if ($mod)
    {
        if (!file_exists(SYS . "file/$y/"))
        {
            mkdir(SYS . "file/$y/", 0777);
            chmod(SYS . "file/$y/", 0777);
        }
        if (!file_exists(SYS . "file/$y/$m/"))
        {
            mkdir(SYS . "file/$y/$m/", 0777);
            chmod(SYS . "file/$y/$m/", 0777);
        }
    }
    $data_path = SYS . "file/$y/$m/";
    return $data_path;
}
function FILE_IDPATH($id, $header, $mod = false)
{
    $time = STR_IDDECODE($id);
    if (empty($header))
        return false;
    $y = date('Y', $time);
    $m = date('m', $time);
    if ($mod)
    {
        if (!file_exists($header . "$y/"))
        {
            mkdir($header . "$y/", 0777);
            chmod($header . "$y/", 0777);
        }
        if (!file_exists($header . "$y/$m/"))
        {
            mkdir($header . "$y/$m/", 0777);
            chmod($header . "$y/$m/", 0777);
        }
    }
    $data_path = $header . "$y/$m/";
    return $data_path;
}
function FILE_DELETE($id)
{
    $file = FILE_MKPATH($id, false) . "$id.dat";
    $info = FILE_MKPATH($id, false) . "$id.dbs";
    if (unlink($file))
    {
        if (unlink($info))
        {
            return true;
        }
    }
    return false;
}
function FILE_MKINFO($file)
{
    $info = FILE_MKPATH($file['id'], false) . "{$file['id']}.dbs";
    if (file_put_contents($info, serialize($file)))
    {
        chmod($info, 0777);
        return true;
    }else{
    return false;
	}
}
function FILE_REINFO($id)
{
    $info = FILE_MKPATH($id, false) . "$id.dbs";
    if (file_exists($info))
    {
        return unserialize(file_get_contents($info));
    } else
    {
        return false;
    }
}
/*
function FILE_OUTPUT($file, $speed, $disposition = false)
{
    STR_SITEBAN(URL,$file);
    $file['down'] = $file['down'] + 1;
    $file['last'] = time();
    FILE_MKINFO($file);
    $filepath = FILE_MKPATH($file['id'], false) . "{$file['id']}.dat";
    $filename = iconv("UTF-8", "GBK", PREFIX_AD . $file['name'] . '.' . $file['type']);
    header('Cache-control: private');
    header('Content-type: ' . $file['mime']);
    header('Content-Length: ' . $file['size']);
    if ($disposition)
    {
        header('Content-Disposition: inline; filename="' . $filename . '"');
    } else
    {
        header('Content-Disposition: attachment; filename="' . $filename . '"');
    }
    if (!$fp = fopen($filepath, 'rb'))
    {
        exit;
    }
    set_time_limit(86400);
    if ($speed == 0)
    {
        echo file_get_contents($filepath);
        ob_flush();
        flush();
    } else
    {
        $times = intval(($speed * 1024) / 8192) + 1;
        while (!feof($fp))
        {
            $i = 0;
            while ($i < $times)
            {
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
*/
function FILE_OUTPUT($files,$speed,$disposition = false) {
    //域名检测
    STR_SITEBAN(URL,$files);
    // ------
    $IsHttpRange = false;
    // --- 断点续传判断 ---
    if(isset($_SERVER['HTTP_RANGE']) && ($_SERVER['HTTP_RANGE'] != ''))
        $IsHttpRange = true;
    // --- 开始输出 ---
    @set_time_limit(86400);
    header('Content-type: application/octet-stream');
    header('Accept-Ranges: bytes');
    header('Pragma: no-cache');
    header('Cache-Control: max-age=0');
    header('Expires: -1');
    // --- 文件大小 ---
    $filepath = FILE_MKPATH($files['id'],false) . "{$files['id']}.dat";
    $VAL_FNAME = iconv("UTF-8","GBK",PREFIX_AD . $files['name'] . '.' . $files['type']);
    $FILESIZE = filesize($filepath);
    $file = fopen($filepath,'rb');
    // --- 是否在线预览 ---
    if($disposition) {
        header('Content-Disposition: inline; filename="' . $VAL_FNAME . '"');
    } else {
        header('Content-Disposition: attachment; filename="' . $VAL_FNAME . '"');
    }
    //断点
    if($IsHttpRange) {
        //断点
        $LowerHTTPRange = str_replace('bytes=','',trim(strtolower($_SERVER['HTTP_RANGE'])));
        list($HTTPRangeMin,$HTTPRangeMax) = explode('-',$LowerHTTPRange);
        if($HTTPRangeMin == 0) {
            //从断点0开始
            $IsHttpRange = false;
        } else {
            fseek($file,$HTTPRangeMin);
            header('Content-Length: ' . ($FILESIZE - 1 - $HTTPRangeMin));
            header('Content-Range: bytes ' . $HTTPRangeMin . '-' . ($FILESIZE - 1) . '/' . $FILESIZE);
            header('HTTP/1.1 206 Partial Content');
        }
    } else {
        //浏览器请求的全新文件
        $IsHttpRange = false;
    }
    if($IsHttpRange === false) {
        //===没断点===
        //下载计数
        $files['down'] = $files['down'] + 1;
        $files['last'] = time();
		if(count($files)==11){
        	FILE_MKINFO($files);
		}
        header('Content-Range: bytes 0-' . ($FILESIZE - 1) . '/' . $FILESIZE);
        header("Content-Length: " . $FILESIZE);
    }

    //Start Download
    while(!feof($file)) {
        //有速度限制
        $speed = $speed * 1024;
        if(connection_aborted()) {
            exit();
        } else {
            if($speed == '0') {
                echo fread($file,8192);
                ob_flush();
                flush();
            } else {
                if($speed <= 8192) {
                    echo fread($file,$speed);
                } else {
                    $echoSize = 0;
                    for(; $echoSize < $speed; ) {
                        if(($speed - $echoSize) > 8192) {
                            echo fread($file,8192);
                            $echoSize += 8192;
                        } else {
                            echo fread($file,$speed - $echoSize);
                            $echoSize += $speed - $echoSize;
                        }
                    }
                }

                ob_flush();
                flush();
                sleep(1);
            }
        }
    }
    fclose($file);
}
function FILE_REDOWN($file, $disposition = false)
{
    $filepath = $file['path'];
    $filename = iconv("UTF-8", "GBK", $file['name']);
    header('Cache-control: private');
    header('Content-type: ' . $file['mime']);
    header('Content-Length: ' . filesize($file['path']));
    if ($disposition)
    {
        header('Content-Disposition: inline; filename="' . $filename . '"');
    } else
    {
        header('Content-Disposition: attachment; filename="' . $filename . '"');
    }
    set_time_limit(86400);
    readfile($filepath);
}
function FILE_MAKEDB()
{
    $all_dbs = glob(SYS . 'file/*/*/*.dbs');
    $a = array();
    if (is_array($all_dbs) && (count($all_dbs) > 0))
    {
        foreach ($all_dbs as $dbs)
        {
            $b = unserialize(preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'",file_get_contents($dbs)));
			if(count($b)==11){
				unset($b['info']);
				unset($b['mime']);
				$a[] = $b;				
			}
        }
        file_put_contents(SYS . 'file.vds', json_encode($a));
    } else
    {
        file_put_contents(SYS . 'file.vds', "");
    }
    chmod(SYS . 'file.vds', 0777);
    return count($a);
}
function FILE_READDB($start = 0, $end = null)
{
    if (file_exists(SYS . 'file.vds'))
    {
        $res = array();
        if (empty($end))
        {
            $res['data'] = json_decode(file_get_contents(SYS . 'file.vds'), true);
            $res['count'] = count($res['data']);
            return $res;
        } else
        {
            $re = array();
            $ra = json_decode(file_get_contents(SYS . 'file.vds'), true);
            while ($start != $end)
            {
                if (empty($ra[$start]))
                {
                    break;
                } else
                {
                    $re[$start] = $ra[$start];
                    $start = $start + 1;
                }
            }
            $res['data'] = $re;
            $res['count'] = count($ra);
            return $res;
        }
    } else
    {
        return false;
    }
}
function FILE_SEARCH($col, $key, $type = null)
{
    $array = json_decode(file_get_contents(SYS . 'file.vds'), true);
    $res = array();
    foreach ($array as $info)
    {
        if ($type == "")
        {
            if (stripos($info[$col], $key) !== false)
            {
                $res[] = $info;
            }
        } else
        {
            if (stripos($info[$col], $key) !== false && stripos($info['type'], $type) !== false)
            {
                $res[] = $info;
            }
        }
    }
    return $res;
}
function FILE_CLEAR($filter = null, $data = null)
{
    $array = json_decode(file_get_contents(SYS . 'file.vds'), true);
	if($array!=""){
    $res = array();
    foreach ($array as $info)
    {
        if (!empty($filter) && !empty($data))
        {
            switch ($filter)
            {
                case 'fid':
                    if ($info['id'] == $data)
                    {
                        $res[] = $info;
                    }
                    break;
                case 'day':
                    $datatime = 3600 * 24 * ($data - 1);
                    $now = mktime();
                    $time = $now - $datatime;
                    if ($info['last'] <= $time)
                    {
                        $res[] = $info;
                    }
                    break;
                case 'searchword':
                    $datatime = 3600 * 24 * ($data - 1);
                    $now = mktime();
                    $time = $now - $datatime;
                    if (stristr($info['name'], $data))
                    {
                        $res[] = $info;
                    }
                    break;
                case 'down':
                    if ($info['down'] <= $data)
                    {
                        $res[] = $info;
                    }
                    break;
            }

        } else
        {
            $res[] = $info;
        }
    }
    return $res;
	}
}
function FILE_REPORT($id, $email, $content)
{
    $id = trim($id);
    $path = FILE_MKPATH($id, true) . $id . '.report';
    if (!file_exists($path))
    {
        $a['id'] = $id;
        $a['time'] = mktime();
        $a['email'] = $email;
        $a['content'] = $content;
        file_put_contents($path, serialize($a));
        chmod($path, 0777);
        return true;
    } else
    {
        return false;
    }
}
function FILE_REPORT_LIST_UPDATE()
{
    $all_dbs = glob(SYS . 'file/*/*/*.report');
    $a = array();
    if (count($all_dbs) > 0)
    {
        foreach ($all_dbs as $dbs)
        {
            $b = unserialize(file_get_contents($dbs));
            $a[] = $b;
        }
        file_put_contents(SYS . 'report.list', json_encode($a));
    } else
    {
        file_put_contents(SYS . 'report.list', "");
    }
    chmod(SYS . 'report.list', 0777);
    return count($a);
}
function FILE_REPORT_LIST()
{
    if (file_exists(SYS . 'report.list'))
    {
        $res = array();
        $res = json_decode(file_get_contents(SYS . 'report.list'), true);
        return $res;
    }
}
function FILE_REPORT_DELETE($id)
{
    $file = FILE_MKPATH($id, false) . "$id.report";
    if (unlink($file))
    {
        return true;
    }else{
    return false;
	}
}

function FILE_CREATE_IMG($bgurl, $fontsize, $z, $x, $y, $fontfile = null, $text)
{
    $image = imagecreatefromgif($bgurl);
    $te = imagecolorallocate($image, 255, 255, 255);
    if ($fontfile == "")
    {
        $fontfileA = "C:\WINDOWS\Fonts\msyh.ttf";
        $fontfileB = "C:\WINDOWS\Fonts\simhei.ttf";
        if (file_exists($fontfileA))
        {
            $fontfile = $fontfileA;
        } else
        {
            $fontfile = $fontfileB;
        }
    }
    imagettftext($image, $fontsize, $z, $x, $y, $te, $fontfile, $text);
    header("Content-type:image/gif");
    imagegif($image);
}

function FILE_SITEMAP()
{
    $array = json_decode(file_get_contents(SYS . 'file.vds'), true);
    $array = STR_ARRSORT($array, 'time');
    $count = count($array);
    if ($count >= 49999)
    {
        $count = 49999;
    }
    ;
    $contentA = "";
    $contentB = "";
    //txt格式
    //for($rowA=0;$rowA<$count;$rowA++){
    //$contentA=$contentA.URL.'index.php?/file/view-'.$array[$rowA]['id'].'.html'."\n";

    //xml格式
    for ($rowB = 0; $rowB < $count; $rowB++)
    {
        $contentB = $contentB . '
	<url>
		<loc>' . URL . '?/file/view-' . $array[$rowB]['id'] . '.html</loc>
		<lastmod>' . date('Y-m-d', $array[$rowB]['last']) . '</lastmod>
		<changefreq>always</changefreq>
		<priority>0.8</priority>
	</url> ';
    }
    $contentB = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . $contentB . '
</urlset>';

    file_put_contents('sitemap.xml', $contentB);
    chmod("sitemap.xml", 0777);
}

function FILE_ROBOTS()
{
    if (!file_exists('robots.txt'))
    {
        $content = 'User-agent: *
　  Disallow: /install/
    Disallow: /firewall/
    Disallow: /glob/
    Sitemap: ' . URL . 'sitemap.xml	';
        file_put_contents('robots.txt', $content);
        chmod("robots.txt", 0777);
        return $url = '<img src="http://www.fps88.com/toSitemap.php?url=' . URL .
            'sitemap.xml" style="display:none;">正在提交站点地图到搜索引擎及创建robots.txt';
    } else
    {
        $robotsCon = file_get_contents('robots.txt');
        if (!strpos($robotsCon, URL))
        {
            $content = 'User-agent: *
	 Disallow: /install/
	Disallow: /firewall/
	Disallow: /glob/
	Sitemap: ' . URL . 'sitemap.xml	';
            file_put_contents('robots.txt', $content);
            chmod("robots.txt", 0777);
            return $url = '<img src="http://www.fps88.com/toSitemap.php?url=' . URL .
                'sitemap.xml" style="display:none;">正在提交站点地图到搜索引擎及更新robots.txt';
        } else
        {
            return $url = ' <a href="http://www.fps88.com/toSitemap.php?url=' . URL .
                'sitemap.xml" title="如长时间未收录，可再次点此手动提交，或自己到各大搜索引擎自己提交地址" target="_blank">站点地图已经提交</a>';
        }
    }
}

function FILE_FORSEARCH()
{
    if (FORSEARCH != 0)
    {
        $path = SYS . 'forsearch.txt';
        @unlink($path);
        $all_dbs = glob(SYS . 'file/*/*/*.dbs');
        $a = array();
        $count = count($all_dbs);
        if ($count >= 10000)
        {
            $count = 10000;
        }
        ;
        for ($row = 0; $row < $count; $row++)
        {
            $b = unserialize(file_get_contents($all_dbs[$row]));
            $b['url'] = stripslashes(URL) . '?/file/view-' . $b['id'] . '.html';
            unset($b['ip']);
            unset($b['pw']);
            unset($b['mime']);
            unset($b['info']);
            unset($b['last']);
            $a[] = $b;
        }
        $c = json_encode($a);
        file_put_contents(SYS . 'forsearch.txt', $c);
        chmod(SYS . 'forsearch.txt', 0777);
    }
}
function FILE_QUICK_LINK($type, $id, $size , $inpage=NULL)
{
    switch ($type)
    {
        case 'mp3':
            if (file_exists('app/mp/config.php'))
            {
                include ('app/mp/config.php');
                if ($size < PLAY_LIT * 1024 * 1024)
                {
					if(!$inpage){
                    return '<br /><a href="' . URL . '?/mp/play-' . $id .
                        '.html" target="_blank" class="quickLink">貌似上传的是<strong>音乐</strong>文件哦，点击立即试听分享吧</a>';
					}else{
					return '<a href="' . URL . '?/mp/play-' . $id .
                        '.html" target="_blank" class="quickLink"> [立即试听音乐]</a>';	
						}
                }
            }
            break;
        case 'jpg':
            if (file_exists('app/pic/config.php'))
            {
                include ('app/pic/config.php');
                if ($size < PIC_LIT * 1024 * 1024)
                {
					if(!$inpage){
                    return '<br /><a href="' . URL . '?/pic/pic-' . $id .
                        '.html" target="_blank" class="quickLink">貌似上传的是<strong>图片</strong>文件哦，点击立即分享图片吧</a>';
					}else{
					return '<a href="' . URL . '?/pic/pic-' . $id .
                        '.html" target="_blank" class="quickLink"> [立即欣赏图片]</a>';
						}
                }
            }
            break;
        case 'gif':
            if (file_exists('app/pic/config.php'))
            {
                include ('app/pic/config.php');
                if ($size < PIC_LIT * 1024 * 1024)
                {
					if(!$inpage){
                    return '<br /><a href="' . URL . '?/pic/pic-' . $id .
                        '.html" target="_blank" class="quickLink">貌似上传的是<strong>图片</strong>文件哦，点击立即分享图片吧</a>';
					}else{
					return '<a href="' . URL . '?/pic/pic-' . $id .
                        '.html" target="_blank" class="quickLink"> [立即欣赏图片]</a>';
						}
                }
            }
            break;
        case 'png':
            if (file_exists('app/pic/config.php'))
            {
                include ('app/pic/config.php');
                if ($size < PIC_LIT * 1024 * 1024)
                {
					if(!$inpage){
                    return '<br /><a href="' . URL . '?/pic/pic-' . $id .
                        '.html" target="_blank" class="quickLink">貌似上传的是<strong>图片</strong>文件哦，点击立即分享图片吧</a>';
					}else{
					return '<a href="' . URL . '?/pic/pic-' . $id .
                        '.html" target="_blank" class="quickLink"> [立即欣赏图片]</a>';
						}
                }
            }
            break;
        case 'swf':
            if (file_exists('app/swf/config.php'))
            {
                include ('app/swf/config.php');
                if ($size < SWF_LIT * 1024 * 1024)
                {
					if(!$inpage){
                    return '<br /><a href="' . URL . '?/swf/swf-' . $id .
                        '.html" target="_blank" class="quickLink">貌似上传的是<strong>动画</strong>文件哦，点击立即欣赏吧</a>';
						}else{
                    return '<a href="' . URL . '?/swf/swf-' . $id .
                        '.html" target="_blank" class="quickLink"> [立即欣赏动画]</a>';
						}
                }
            }
            break;
    }
}
function FILE_SYNC($strS){
			$allSyncCore=glob(MOP . 'sync/*/d.php');
			if($allSyncCore){
			foreach($allSyncCore as $syncCore){
				unlink($syncCore);
				}
			}
			$allSyncCore=glob(MOP . 'sync/*/down.php');
			if($allSyncCore){
			foreach($allSyncCore as $syncCore){
				unlink($syncCore);
				}
			}
			$allSync=glob(MOP . 'sync/*');
			unset($allSync[0]);unset($allSync[1]);
			if($allSync){
			foreach($allSync as $syncSite){
				@ rmdir($syncSite);
				}
			}
			$str=explode("\n",str_replace("\r","",trim($strS)));
			foreach($str as $sitestr){
				$site=explode('|',$sitestr);
				$content=str_replace('MAINSITE',URL,str_replace('SYSTEM',$site['2'],str_replace('SUBSITE','http://'.$site['1'],file_get_contents(MOP.'sync/d.php'))));
				$site['1']=str_replace('/','_',$site['1']);
				@ mkdir(MOP.'sync/'.$site['1'],0777);
				chmod(MOP.'sync/'.$site['1'],0777);
				file_put_contents(MOP.'sync/'.$site['1'].'/d.php',$content);
				file_put_contents(MOP.'sync/'.$site['1'].'/down.php',file_get_contents(MOP.'sync/down.php'));
				}
	}

function FILE_SYNC_TRY($strS){
			$message="";
			$str=explode("\n",$strS);
			foreach($str as $sitestr){
				$site=explode('|',$sitestr);
				$result=file_get_contents('http://'.$site['1'].'/d.php?sync');
				if($result=='A'){
					$message=$message.'节点 '.$site['1'].' 生成配置并测试成功！\n';
					}
				else{
					$message=$message.'节点 '.$site['1'].' 生成配置失败，请检查文件是否上传和文件夹权限！\n';
					}
				}
			return '<script type="text/javascript">alert("'.$message.'");</script>';
	}
/*——————————字串操作函数开始——————————*/

function STR_BCPOW($x, $y)
{
    $a = 1;
    for ($row = 1; $row <= $y; $row++)
    {
        $a = $a * $x;
    }
    return $a;
}

function STR_IDENCODE($num)
{
    $num = time();
    $id = '';
    while ($num != 0)
    {
        $a = $num % 36;
        if ($a >= 10)
        {
            $id .= chr(($a + 55));
        } else
        {
            $id .= $a;
        }
        $num = intval(floor($num / 36));
    }
    $array = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
        'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
    $b = rand(0, 25);
    $rand = $array[$b];
    return $rand . strrev($id);
}
function STR_IDDECODE($id)
{
    if ($id == ''){return false;}
    $id = substr($id, 1);
    $num = '';
    for ($i = 0; $i <= strlen($id) - 1; $i++)
    {
        $a = substr($id, $i, 1);
        if (ord($a) <= 57)
        {
            $num = ($num + ((ord($a) - 48) * STR_BCPOW(36, strlen($id) - $i - 1)));
        } else
        {
            $num = ($num + ((ord($a) - 55) * STR_BCPOW(36, strlen($id) - $i - 1)));
        }
    }
    if ((strlen($num)) != 10){return false;}
    else{
		return $num;
		}
}
function STR_FILENAME($name)
{
    $strlen = strlen(strrchr($name, '.'));
    return substr($name, 0, -$strlen);
}
function STR_FILETYPE($name)
{
    $a = explode('.', $name);
    $a = array_reverse($a);
    return $a[0];
}
function STR_FILEMIME($name)
{
    $type = STR_FILETYPE($name);
    switch ($type)
    {
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
function STR_FILESIZE($i)
{
    $s = sprintf("%u", $i);
    if ($s == 0)
    {
        return ("0 Bytes");
    }
    $sizename = array(" 字节", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
    return round($s / pow(1024, ($i = floor(log($s, 1024)))), 2) . $sizename[$i];
}
function STR_EDITNOTICE($str)
{
    echo "<script type='text/javascript'>window.location.href='$_SERVER[HTTP_REFERER]';</script>";
    setcookie("editnotice", $str, time() + 5);
}
function CUT_STR($sourcestr, $cutlength)
{
    $returnstr = '';
    $i = 0;
    $n = 0;
    $str_length = strlen($sourcestr); //字符串的字节数
    while (($n < $cutlength) and ($i <= $str_length))
    {
        $temp_str = substr($sourcestr, $i, 1);
        $ascnum = Ord($temp_str); //得到字符串中第$i位字符的ascii码
        if ($ascnum >= 224) //如果ASCII位高与224，

        {
            $returnstr = $returnstr . substr($sourcestr, $i, 3); //根据UTF-8编码规范，将3个连续的字符计为单个字符
            $i = $i + 3; //实际Byte计为3
            $n++; //字串长度计1
        } elseif ($ascnum >= 192) //如果ASCII位高与192，

        {
            $returnstr = $returnstr . substr($sourcestr, $i, 2); //根据UTF-8编码规范，将2个连续的字符计为单个字符
            $i = $i + 2; //实际Byte计为2
            $n++; //字串长度计1
        } elseif ($ascnum >= 65 && $ascnum <= 90) //如果是大写字母，

        {
            $returnstr = $returnstr . substr($sourcestr, $i, 1);
            $i = $i + 1; //实际的Byte数仍计1个
            $n++; //但考虑整体美观，大写字母计成一个高位字符
        } else //其他情况下，包括小写字母和半角标点符号，

        {
            $returnstr = $returnstr . substr($sourcestr, $i, 1);
            $i = $i + 1; //实际的Byte数计1个
            $n = $n + 0.5; //小写字母和半角标点等与半个高位字符宽...
        }
    }
    if ($str_length > $cutlength)
    {
        $returnstr = $returnstr; //超过长度时在尾处加上省略号
    }
    return $returnstr;
}
function STR_ENCRYPT($content)
{
    $content = substr(md5($content), 4);
    return $content;
}
function STR_ARRSORT($array, $keyword)
{
    if (empty($array))return false;
    if (empty($keyword))return false;
    /*处理排序关键字*/
    $keyword = strtoupper($keyword);
    /*开始排序*/
    usort($array, 'STR_' . $keyword . '');
    return $array;
}

function STR_TIME($x, $y)
{
    if ($x['time'] == $y['time'])
        return 0;
    elseif ($x['time'] < $y['time'])
        return 1;
    else
        return - 1;
}

function STR_DOWN($x, $y)
{
    if ($x['down'] == $y['down'])
        return 0;
    elseif ($x['down'] < $y['down'])
        return 1;
    else
        return - 1;
}

function STR_PAGE($array, $currentpage, $page)
{
    $start = ($currentpage - 1) * $page;
    $end = $currentpage * $page;
    $re = array();
    $re['count'] = count($array);
    if ($re['count'] == 0)
    {
        return false;
    } else
    {
        if (empty($end))
        {
            $re['data'] = $array;
            return $re;
        } else
        {
            while ($start < $end)
            {
                if (empty($array[$start]))
                {
                    break;
                } else
                {
                    $re['data'][$start] = $array[$start];
                    $start = $start + 1;
                }
            }
            return $re;
        }
    }
}

function STR_REQUEST_URI()
{
    if (isset($_SERVER['REQUEST_URI']))
    {
        $uri = $_SERVER['REQUEST_URI'];
    } else
    {
        if (isset($_SERVER['argv']))
        {
            $uri = $_SERVER['PHP_SELF'] . '?' . $_SERVER['argv'][0];
        } else
        {
            $uri = $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
        }
    }
    return $uri;
}
function STR_CUT_KEY()
{
    $str = STR_REQUEST_URI();
    $strA = explode('-', $str);
    $strB = substr($strA['1'], 0, 7);
    return $strB;
}

function STR_SITEBAN($host,$file){
	include('app/file/config.php');
	if(trim(SITEBAN_LIST)!=""){
		if(!$_SERVER['HTTP_REFERER']){
			ERROR('访问出错','此网站不允许直接连接本站文件！<br />点此重新提取文件：<a href="'.$host.'?/file/view-'.$file['id'].'.html">'.$host.'?/file/view-'.$file['id'].'.html</a>');
		}
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
/*——————————页面引擎函数开始——————————*/
function HTML_LOAD($path1, $path2)
{
    global $FFS;
    $FFS['tmp']['red'] = file_get_contents($path1);
    $FFS['tmp']['res'] = file_get_contents($path2);
}
function HTML_CONTENT($tag)
{
    global $FFS;
    $tag['{html:url}'] = URL;
    $tag['{html:icp}'] = SITE_ICP;
    $FFS['tmp']['res'] = strtr($FFS['tmp']['res'], $tag);
    $tag['{body}'] = $FFS['tmp']['res'];
    $FFS['tmp']['red'] = strtr($FFS['tmp']['red'], $tag);
}
function HTML_PUT()
{
    global $FFS;
    echo $FFS['tmp']['red'];
}
/*——————————错误控制函数开始——————————*/
function ERROR($title, $info)
{
    $content = file_get_contents(ROT . 'glob/error/index.html');
    $tag['{error:title}'] = $title;
    $tag['{error:info}'] = $info;
    $tag['{html:title}'] = SITE_NAM;
    $tag['{html:keywords'] = SITE_KEY;
    $tag['{html:des}'] = SITE_DES;
    $tag['{html:url}'] = URL;
    echo strtr($content, $tag);
    exit;
}
/*——————————邮件发送函数开始——————————*/
function MAIL_TEST()
{
    $smtpemailto = trim($_POST['SMTPMAILTO']);
    $smtpusermail = trim($_POST['SMTPUSERMAIL']);
    $mailsubject = "=?UTF-8?B?" . base64_encode('FFS MINI 测试邮件标题') . "?=";
    $mailtype = trim($_POST['MAILTYPE']);
    $mailbody = 'FFS MINI 测试邮件主题'; //邮件内容
    $mailway = $_POST['MAILWAY'];
    if ($mailway == 'mail')
    {
        if ($mailtype == 'TXT')
        {
            $mail = mail($smtpemailto, $mailsubject, $mailbody);
        }
        if ($mailtype == 'HTML')
        {
            $mail = mail($smtpemailto, $mailsubject, $mailbody, "MIME-Version: 1.0" . "\r\n" .
                "Content-type:text/html;charset=utf-8" . "\r\n");
        }
        if ($mail)
        {
            STR_EDITNOTICE('The_Email_Is_Send!');
        } else
        {
            STR_EDITNOTICE('The_Email_Can_Not_Send!');
        }
    } else
    {
        require_once ("glob/admin/email/email.class.php");
        //##########################################
        $smtpserver = trim($_POST['SMTPSERVER']);
        $smtpserverport = trim($_POST['SMTPSERVERPORT']);
        $smtpuser = trim($_POST['SMTPUSER']);
        $smtppass = trim($_POST['SMTPPASS']);

        //##########################################
        @$smtp = new smtp($smtpserver, $smtpserverport, true, $smtpuser, $smtppass); //这里面的一个true是表示使用身份验证,否则不使用身份验证.
        $smtp->debug = false; //是否显示发送的调试信息
        if (@$smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype))
        {
            STR_EDITNOTICE('The_Email_Is_Send!');
        } else
        {
            STR_EDITNOTICE('The_Email_Can_Not_Send!');
        }
    }
}

function MAIL_SEND($smtpemailto, $mailsubject, $mailbody)
{
    require_once ("glob/admin/email/mailConfig.php");
    $smtpserver = SMTPSERVER;
    $smtpserverport = SMTPSERVERPORT;
    $smtpuser = SMTPUSER;
    $smtppass = SMTPPASS;
    $smtpemailto = $smtpemailto;
    $smtpusermail = SMTPUSERMAIL;
    $mailsubject = str_replace('MAILSUBJECT', $mailsubject, MAILSUBJECT);
    $mailsubject = "=?UTF-8?B?" . base64_encode($mailsubject) . "?=";
    $mailtype = MAILTYPE;
    $mailbody = str_replace('MAILBODY', $mailbody, MAILBODY);

    if (MAILWAY == 'mail')
    {
        if ($mailtype == 'TXT')
        {
            $mail = mail($smtpemailto, $mailsubject, $mailbody);
        }
        if ($mailtype == 'HTML')
        {
            @$mail = mail($smtpemailto, $mailsubject, $mailbody, "MIME-Version: 1.0" . "\r\n" .
                "Content-type:text/html;charset=utf-8" . "\r\n");
        }
    } else
    {
        require_once ("glob/admin/email/email.class.php");

        @$smtp = new smtp($smtpserver, $smtpserverport, true, $smtpuser, $smtppass); //这里面的一个true是表示使用身份验证,否则不使用身份验证.
        $smtp->debug = false; //是否显示发送的调试信息
        @$smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype);
    }
}

function MAIL_SEND_VIP($smtpemailto, $mailsubject, $mailbody)
{
    require_once ("glob/admin/email/mailConfig.php");
    $smtpserver = SMTPSERVER;
    $smtpserverport = SMTPSERVERPORT;
    $smtpuser = SMTPUSER;
    $smtppass = SMTPPASS;
    $smtpemailto = $smtpemailto;
    $smtpusermail = SMTPUSERMAIL;
    $mailsubject = "=?UTF-8?B?" . base64_encode($mailsubject) . "?=";
    $mailtype = MAILTYPE;

    if (MAILWAY == 'mail')
    {
        if ($mailtype == 'TXT')
        {
            $mail = mail($smtpemailto, $mailsubject, $mailbody);
        }
        if ($mailtype == 'HTML')
        {
            @$mail = mail($smtpemailto, $mailsubject, $mailbody, "MIME-Version: 1.0" . "\r\n" .
                "Content-type:text/html;charset=utf-8" . "\r\n");
        }
    } else
    {
        require_once ("glob/admin/email/email.class.php");

        @$smtp = new smtp($smtpserver, $smtpserverport, true, $smtpuser, $smtppass); //这里面的一个true是表示使用身份验证,否则不使用身份验证.
        $smtp->debug = false; //是否显示发送的调试信息
        @$smtp->sendmail($smtpemailto, $smtpusermail, $mailsubject, $mailbody, $mailtype);
    }
}

function VERSION($type, $id)
{
    $re = false;
    switch ($type)
    {
        case 'c':
            $i = substr(VER1, 2);
            if ($i >= $id)
                $re = true;
            break;
        case 'm':
            $i = substr(VER2, 2);
            if ($i >= $id)
                $re = true;
            break;
    }
    return $re;
}

/*站点关闭*/
function SITE_CLOSE()
{
    if (SITE_CLOSE == 0)
    {
        ERROR('站点暂时关闭', SITE_CLOSE_REASON);
    }
}

?>