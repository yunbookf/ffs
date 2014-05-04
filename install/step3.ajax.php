<?php
include '../glob.php';

if(!is_file('install.lock')) {
	ob_start();

	$_POST['pwd'] = trim($_POST['pwd']);
	if($_POST['pwd']!='') {
		$_POST['username'] = trim($_POST['username']);
		if($_POST['username']!='') {
			// --- 整理数据 ---
			$context = '<?php '."\n";
			$context=$context.'define(\'ADMIN_PASSWORD\',\''.STR_ENCRYPT(trim($_POST['pwd'])).'\'); '."\n";
			$context=$context.' ?>';	
			file_put_contents('../glob/admin/config.php', $context);
			chmod('../glob/admin/config.php', 0777);
			echo json_encode(array(
				'result' => '1'
			));
		} else {
			echo json_encode(array(
				'result' => '0',
				'msg' => '请输入创始人账户'
			));
		}
	} else {
		echo json_encode(array(
			'result' => '0',
			'msg' => '请输入创始人密码'
		));
	}
}

