<?php
if(!is_file('install.lock')) {
	/*
	xNet::post('http://program.maiyun.net/products/filiant/install_callback.php', array(
		'qq' => $_POST['qq'],
		'email' => $_POST['email'],
		'mphone' => $_POST['mphone'],
		'url' => $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']
	));
	//*/
	// --- 锁定 ---
	rename('_install.lock', 'install.lock');
	echo json_encode(array(
		'result' => '1'
	));
}

