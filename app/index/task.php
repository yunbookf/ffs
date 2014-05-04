<?php
if(RUN){
	/*开始读取全部数据*/
	include_once(ROT.'app/index/config.php');
	if(INDEX_UPDATE){
	$all = FILE_READDB();
	if($all['count']==0){
	/*保存记录*/
	file_put_contents(ROT.'app/index/update.vds','N');
	}else{
	/*生成最近上传*/
	$newupload = STR_ARRSORT($all['data'],'time');
	/*生成热门下载*/
	$hotdown   = STR_ARRSORT($all['data'],'down');
	/*根据配置，取定量数据*/
	for($a=0;$a<INDEX_UPDATE_NUMS;$a++){
		$new_upload[$a] = $newupload[$a];
	}
	for($a=0;$a<INDEX_UPDATE_NUMS;$a++){
		$hot_down[$a] = $hotdown[$a];
	}
	$res['new'] = $new_upload;
	$res['hot'] = $hot_down;
	/*保存记录*/
	file_put_contents(ROT.'app/index/update.vds',serialize($res));
	}
	}
}
?>