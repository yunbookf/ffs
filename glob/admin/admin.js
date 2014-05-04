// JavaScript Document
//$(document).ready(function(){
//$(".table tr:even").addClass("alt"); //给class为table的表格的偶数行添加class值为alt
//});


function delCookie(name){//为了删除指定名称的cookie，可以将其过期时间设定为一个过去的时间
   var date = new Date();
   date.setTime(date.getTime() - 10000);
   document.cookie = name + "=a; expires=" + date.toGMTString();
} 
function getCookie(name)//取cookies函数        
{
    var arr = document.cookie.match(new RegExp("(^| )"+name+"=([^;]*)(;|$)"));
     if(arr != null) return unescape(arr[2]); return null;
}


function editNotice(){
	if(getCookie('editnotice')!=null){
		var a=getCookie('editnotice');
		document.getElementById("result").innerHTML   = '<p>'+a+'</p>'; 
		}
	}

$(document).ready(function(){
//复选框全选，以及选中背景之类	
$('.fileslist .table_title').click(
function(){
   if($(this).hasClass('alt'))
   {
		$('.fileslist').find('input[type="checkbox"]').removeAttr('checked');
		$(this).removeClass('alt');
		$('.fileslist tbody tr').removeClass('alt');
	   }
	   else
	   {
		  $('.fileslist').find('input[type="checkbox"]').attr('checked','checked'); 
		  $(this).addClass('alt');
		  $('.fileslist tbody tr').addClass('alt');
		   }
}
);		
 $('.fileslist tbody tr').click(
  function() {
   if ($(this).hasClass('alt')) {
    $(this).removeClass('alt');
    $(this).find('input[type="checkbox"]').removeAttr('checked');
   } else {
    $(this).addClass('alt');
    $(this).find('input[type="checkbox"]').attr('checked','checked');
   }
  }
  
 );
 
 
 //关于邮件设置
 function wayMail(){
	 if($('#mailway').val()!='sendmail'){
		 $('#wayMail').attr('checked','checked');
		 $('.forSendmail').hide();
		 }
	 else{
		 $('#waySendmail').attr('checked','checked');		  
		  }
	$('#waySendmail').click(
		function(){
		$('.forSendmail').show();;	
			}
		)
		$('#wayMail').click(
		function(){
		$('.forSendmail').hide();	
			}
		)
	 
	 
	 }
wayMail();
 
//顶部搜索框
$('.top_search input[type="text"]').focus(
	function(){
		$(this).val("");
			}
)
editNotice(); 
});
