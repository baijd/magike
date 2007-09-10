/******************
Magike UI Project
Powered by Magike Group
Based On jQuery
******************/

//获取滚动位置
function getScrollTop()
{
	var yScrolltop;
	var xScrollleft;
	if (self.pageYOffset || self.pageXOffset) {
		yScrolltop = self.pageYOffset;
		xScrollleft = self.pageXOffset;
	} else if (typeof(document.documentElement) != "undefined" && typeof(document.documentElement.scrollTop) != "undefined" || typeof(document.documentElement.scrollLeft) != "undefined" ){	 // Explorer 6 Strict
		yScrolltop = document.documentElement.scrollTop;
		xScrollleft = document.documentElement.scrollLeft;
	} else if (typeof(document.body) != "undefined") {
		yScrolltop = document.body.scrollTop;
		xScrollleft = document.body.scrollLeft;
	}

	arrayPageScroll = new Array(xScrollleft,yScrolltop);
	return arrayPageScroll;
}

//获取页面大小
function getPageSize()
{
	var de = document.documentElement;
	var w = window.innerWidth || self.innerWidth || (de&&de.clientWidth) || document.body.clientWidth;
	var h = window.innerHeight || self.innerHeight || (de&&de.clientHeight) || document.body.clientHeight;
	arrayPageSize = new Array(w,h);
	return arrayPageSize;
}

MagikeUI = function()
{
	
}

MagikeUI.prototype = {
	//实例化一个窗口
	createPopup: function(args)
	{
		title = args.title ? args.title : 'Magike Window';
		//判断浏览器
		var isIE = $.browser.msie;
		
		//创建窗口外框
		var popupWindow = $(document.createElement("div"));
		popupWindow.addClass("magikePopup");
		popupWindow.released = true;
		popupWindow.x = false;
		popupWindow.y = false;
		
		//创建窗口阴影
		var popupShadow = $(document.createElement("div"));
		popupShadow.addClass("magikePopupShadow");
		
		//创建窗口内部
		var popupContent = $(document.createElement("div"));
		popupContent.addClass("magikePopupContent");
		popupWindow.append(popupContent);
		popupWindow.append(popupShadow);
		
		//创建窗口标题
		var popupTitle = $(document.createElement("div"));
		popupTitle.addClass("magikePopupTitle");
		popupTitle.html(title);
		
		//创建窗口关闭按钮
		var closeBar = $(document.createElement("span"));
		closeBar.addClass("magikePopupClose");
		popupTitle.append(closeBar);
		popupContent.append(popupTitle);
		
		$(document.body).append(popupWindow);
		if(args.center)
		{
			size = getPageSize();
			pos = getScrollTop();
			
			vleft = parseInt((size[0] - popupWindow.width())/2 + pos[0]);
			vtop = parseInt((size[1] - popupWindow.height())/2 + pos[1]);
			popupWindow.css({left:vleft + 'px',top:vtop + 'px'});
		}
		
		//增加事件监听
		popupTitle.mousedown(
			function()
			{
				popupWindow.released = false;
				$('.magikePopup').css('z-index',995);
				$('.magikePopupShadow').css('z-index',996);
				$('.magikePopupContent').css('z-index',997);
				popupWindow.css('z-index',998);
				popupShadow.css('z-index',999);
				popupContent.css('z-index',1000);
				popupShadow.hide();
			}
		);
		
		$(document).mouseup(
			function()
			{
				popupWindow.released = true;
				popupWindow.x = false;
				popupWindow.y = false;
				popupShadow.show();
			}
		);
		
		popupTitle.mousemove(
			function(e)
			{
				if(!popupWindow.released)
				{
					if(isIE ? e.button : !e.button)
					{
						if(!popupWindow.x && !popupWindow.y)
						{
							popupWindow.x = e.clientX;
							popupWindow.y = e.clientY;
						}
						
						popupWindow.css('left',parseInt(popupWindow.css('left').replace('px',''))+(e.clientX - popupWindow.x)+'px');
						popupWindow.css('top',parseInt(popupWindow.css('top').replace('px',''))+(e.clientY - popupWindow.y)+'px');
						popupWindow.x = e.clientX;
						popupWindow.y = e.clientY;
					}
					else
					{
						popupWindow.released = true;
					}
				}
			}
		);
		
		$(document).mousemove(
			function(e)
			{
				
				if(!popupWindow.released)
				{
					if(isIE ? e.button : !e.button)
					{
						if(!popupWindow.x && !popupWindow.y)
						{
							popupWindow.x = e.clientX;
							popupWindow.y = e.clientY;
						}
						
						popupWindow.css('left',parseInt(popupWindow.css('left').replace('px',''))+(e.clientX - popupWindow.x)+'px');
						popupWindow.css('top',parseInt(popupWindow.css('top').replace('px',''))+(e.clientY - popupWindow.y)+'px');
						popupWindow.x = e.clientX;
						popupWindow.y = e.clientY;
					}
					else
					{
						popupWindow.released = true;
					}
				}
			}
		);
		
		$('.magikePopupClose',popupWindow).click(
			function()
			{
				popupWindow.remove();
			}
		);
	},
	
	//创建一个阴影
	createShadow: function(args)
	{
		var shadow = $(document.createElement("div"));
		shadow.addClass("magikeShadow");
		shadow.css('width',document.documentElement.scrollWidth > document.documentElement.clientWidth ? 
		document.documentElement.scrollWidth : document.documentElement.clientWidth);
		shadow.css('height',document.documentElement.scrollHeight > document.documentElement.clientHeight ? 
		document.documentElement.scrollHeight : document.documentElement.clientHeight);
		$(document.body).append(shadow);
		
		$(window).resize(
			function()
			{
				shadow.css('width',document.documentElement.scrollWidth > document.documentElement.clientWidth ? 
				document.documentElement.scrollWidth : document.documentElement.clientWidth);
				shadow.css('height',document.documentElement.scrollHeight > document.documentElement.clientHeight ? 
				document.documentElement.scrollHeight : document.documentElement.clientHeight);
			}
		);
	}
};

var MagikeUI = MagikeUI; 
var magikeUI = new MagikeUI();