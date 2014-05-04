// JavaScript Document
function htmlCode(url,mp3list){
	var autostart="";
	var autoplay="";
	var randomplay="";
	var showtime="";
	var mode="";
	var aPlayerMode=document.getElementById('playerMode').getElementsByTagName('input');
	var aDewplayer=document.getElementsByName('version');
	for(i=0;i<aDewplayer.length;i++)
	{
		aDewplayer[i].onclick=function(){
			var dewplayerArr=this.value.split('|');
			var dewplayer=dewplayerArr[0];
			var w=dewplayerArr[1];
			var h=dewplayerArr[2];
			
			for(i=0;i<aPlayerMode.length;i++){
				if(aPlayerMode[i].checked==true)
				{
					thismode=aPlayerMode[i].value;
					}
				else
				{
					thismode="";
					}
				mode=mode+thismode;
				}
			var code='<object type="application/x-shockwave-flash" data="'+url+'app/mp/player/dewplayer-'+dewplayer+'.swf" width="'+w+'" height="'+h+'" id="dewplayer" name="dewplayer"><param name="wmode" value="transparent" /><param name="movie" value="'+url+'app/mp/player/dewplayer-'+dewplayer+'.swf" /><param name="flashvars" value="mp3='+mp3list+mode+'" /></object>';
			document.getElementById('htmlCode').value=code;
			mode="";
			}
		}	
	
	
	for(i=0;i<aPlayerMode.length;i++)
	{
		aPlayerMode[i].onclick=function(){
			for(i=0;i<aDewplayer.length;i++){
				if(aDewplayer[i].checked==true){
					var dewplayerArr=aDewplayer[i].value.split('|');
					var dewplayer=dewplayerArr[0];
					var w=dewplayerArr[1];
					var h=dewplayerArr[2];
					}
			}
			for(i=0;i<aPlayerMode.length;i++){
				if(aPlayerMode[i].checked==true)
				{
					thismode=aPlayerMode[i].value;
					}
				else
				{
					thismode="";
					}
				mode=mode+thismode;
				}
				var code='<object type="application/x-shockwave-flash" data="'+url+'app/mp/player/dewplayer-'+dewplayer+'.swf" width="'+w+'" height="'+h+'" id="dewplayer" name="dewplayer"><param name="wmode" value="transparent" /><param name="movie" value="'+url+'app/mp/player/dewplayer-'+dewplayer+'.swf" /><param name="flashvars" value="mp3='+mp3list+mode+'" /></object>';
			document.getElementById('htmlCode').value=code;
			mode="";
				}
		}		
}
