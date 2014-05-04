// JavaScript Document
function openbox(box){
	document.getElementById(box).style.display='block';
	}
function closebox(box){
	document.getElementById(box).style.display='none';
	}

function moveIco(){ 
	var c=$(".plus_inner img").length;
	$(".plus_inner").css("width",parseInt(c)*108);
	$('.clickLeft').click(function(){
		var a=$(".plus_inner").css("left");
		var d=parseInt(c)-7;
		if(parseInt(a)<=-108*d){return false;}
		var b=parseInt(a)-108;
		$(".plus_inner").css("left",b);
		})
	$('.clickRight').click(function(){
		var a=$(".plus_inner").css("left");
		if(parseInt(a)+108 >0){return false;}
		var b=parseInt(a)+108;
		$(".plus_inner").css("left",b);
		})	
	}

function newTips(){
        $.XYTipsWindow({
            ___title:"Tips效果",
			___triggerID:"header",
            ___content:"text:<p style='margin:1px;padding:8px;background:#FFF9DF'>1，用户系统上线啦<br />2，文件管理更方便了<br />3，音乐分享加入试听列表了<br />4，FFS文件搜索上线啦，<a href=http://so.fps88.com target=_blank >去看看</a></p>",
            ___width:"200",
            ___height:"",
            ___showTitle:false,
            ___showBoxbg:false,
            ___boxWrapBdColor:"#FDB838",
            ___closeID:"colseTipsLayer",
            ___offsets:{left:"800px",top:"50px"},
            ___fns:function(){
                    $("body").append("<span class=\"arrowLeft\" style=\"left:-7px;top:15px;\">箭头</span><em class=\"colseBtn\" id=\"colseTipsLayer\">关闭</em>");
                    $(".arrowLeft,#colseTipsLayer").appendTo("#"+$.XYTipsWindow.getID());
                    $("#"+$.XYTipsWindow.getID()).find(".___boxContent").css({background:"#FFFFFF"}).addClass("tipslayer");
            }
        });
	}
	

function linkCountDown(){
		 var waittime=parseInt($(".linkTime").attr("timer"));		
		 if(waittime>=0){    
		 var seconds =waittime;  
		 msg = "地址获取中，请等候["+seconds+"]秒...";  
		 $(".linkTime").html(msg);
		 --waittime;  
		 $(".linkTime").attr("timer",waittime);
		 }  
		 else{   
		 $(".linkTime").css("display","none"); 
		 $(".linkHidden").css("display","inline"); 
		 } 
	}
	
$(document).ready(function() { 
		setInterval("linkCountDown()",1000); 
		if($("#plus_window").get()!=""){newTips()};
		$(".ireport").click(function(){
			var a=$("#reportKey").val();
			var b=$("#yourEmail").val();
			$.XYTipsWindow({
				___title:"<strong>举报文件</strong>",	
				___content:'text:<div class="alertBox"><form action="index.php" method="post" class="reportForm"><p><label>文件分享码：</label><input type="text" name="id" value="'+a+'"  /></p><p><label>&nbsp;&nbsp;&nbsp;联系邮箱：</label><input type="text" name="email" value="'+b+'"  /></p><p><textarea name="content">请填写理由</textarea></p><p><input type="submit" class="round_btn" name="reportBtn" value="提交" /></p></form></div>',
				___drag:"___boxTitle",
				___height:170,
				___showbg:true
			});
		});

		/*
		$("#infoImgTips").hover(function(){
			var a=$("#reportKey").val();
			$.XYTipsWindow({
		    ___title:"<strong>效果预览</strong>",
            ___triggerID:"infoImgTips",
            ___content:'text:<p style=padding:5px;><img src=?/file/pic-'+a+'.gif /></p>',
            ___offsets:{left:"370px",top:"45px"},
			___height:"168",
			___width:"368"
			});
		},
			function(){
				setTimeout(function(){$.XYTipsWindow.removeBox();},200);
			});
		*/

	
})