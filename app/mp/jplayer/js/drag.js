//创建drag对象
var drag=function(){
  this.position=document.getElementsByTagName('body')[0];
  this.draystar=document.getElementById('playListBox').getElementsByTagName('span')[0];
  this.star=document.getElementById('playListBox');
  this.pos=null;
  this.follow=false;
}

//获取坐标
drag.prototype.getMousePos=function(e){
    var e=window.event||e;
  if(e.pageX||e.pageY){
     return {x:e.pageX,y:e.pageY};
  }
  return{
    x:e.clientX + document.body.scrollLeft-document.body.clientLeft,
    y:e.clientY + document.body.scrollTop-document.body.clientTop
  };
}

//显示鼠标坐标值
drag.prototype.show=function(e){
   this.pos=this.getMousePos(e);
   if(this.follow){this.star.style.cssText='left:'+(this.pos.x-30)+'px;top:'+(this.pos.y-30)+'px';}
}

function getPosition(){
	var x=document.getElementById('playListBox').offsetLeft;
	var y=document.getElementById('playListBox').offsetTop;
	SetCookie('playListBoxLeft',x);
	SetCookie('playListBoxTop',y);
	}

//初始化
drag.prototype.init=function(){
     var that=this;
     document.onmousemove=function(e){that.show(e);}
   this.draystar.onmousedown=function(){that.follow=true;}
   document.onmouseup=function(){that.follow=false;getPosition();}
}



var drag_demo=new drag();
drag_demo.init();