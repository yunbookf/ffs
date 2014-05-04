<?php
/*每日更新虚拟数据库*/
if(RUN){
	$time = file_get_contents(SYS.'vds.task');
	$nowt = time();
	if(($nowt-$time)>TASK_RUNTIME){
		FILE_MAKEDB();
		file_put_contents(SYS.'vds.task',time());
		$all_task = glob(ROT.'app/*/task.php');
		foreach($all_task as $path){
			include_once($path);
		}
	}
/*更新站点地图*/
	$timeA=file_get_contents(SYS.'sitemap.task');
	if(($nowt-$timeA)>SITEMAP_RUNTIME*3600){
		FILE_SITEMAP();
		FILE_REPORT_LIST_UPDATE(); //顺便更新举报列表
		file_put_contents(SYS.'sitemap.task',time());
	}	

/*更新搜索文件*/
	$timeB=file_get_contents(SYS.'forsearch.task');
	if(($nowt-$timeB)>SITEMAP_RUNTIME*3600){
		FILE_FORSEARCH();//更新下搜索引擎文件
		file_put_contents(SYS.'forsearch.task',time());
	}	

}


?>