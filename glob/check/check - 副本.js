$(document).ready(function(){if(!(navigator.userAgent.indexOf("Chrome")>0)){
	var k='未知类型';
	if(!(navigator.userAgent.indexOf("MSIE")<0))k='IE';
	if(!(navigator.userAgent.indexOf("Gecko/")<0))k='Gecko';
	if(!(navigator.userAgent.indexOf("Camino")<0))k='Camino';
	if(!(navigator.userAgent.indexOf("Firefox")<0))k='Firefox';
	var s = '检测到您使用的是'+k+'内核的浏览器，使用本站的过程可能出现运行问题。为了保证您的使用体验，';
$.XYTipsWindow({
				___title:"<strong style=color:blue>浏览体验改进计划</strong>",	
				___content:'text:<div class="alertBox">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'+s+'建议您使用支持HTML5的<span style=color:red> webkit高速内核 </span>的浏览器光临本站，将能获得最佳的界面效果与最快载入速度，<span style=color:blue> 搜狗 </span>和<span style=color:blue> 360浏览器 </span>等其他多核心浏览器请切换到高速模式，<span style=color:red>IE9</span>请切换到兼容模式，推荐您使用<a href="http://www.google.com/chrome/" target=_blank style=color:red> chrome浏览器 </a>。</div>',
				___drag:"___boxTitle",
				___showbg:true,
				___width:"460",
		        ___height:"120"
			});
	}});