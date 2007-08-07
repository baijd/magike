/********************************************
	Magike Project
	copyright (c) Magike Group
	This software must be only used in Magike Systeam.
	http://www.magike.net
	powered by qining
********************************************/

//json 解析扩展
if (!Object.prototype.toJSONString) {
    (function (s) {
        var m = {
            '\b': '\\b',
            '\t': '\\t',
            '\n': '\\n',
            '\f': '\\f',
            '\r': '\\r',
            '"' : '\\"',
            '\\': '\\\\'
        };

        s.parseJSON = function (hook) {
            try {
                if (/^("(\\.|[^"\\\n\r])*?"|[,:{}\[\]0-9.\-+Eaeflnr-u \n\r\t])+?$/.
                        test(this)) {
                    var j = eval('(' + this + ')');
                    if (typeof hook === 'function') {
                        function walk(v) {
                            if (v && typeof v === 'object') {
                                for (var i in v) {
                                    if (v.hasOwnProperty(i)) {
                                        v[i] = walk(v[i]);
                                    }
                                }
                            }
                            return hook(v);
                        }
                        return walk(j);
                    }
                    return j;
                }
            } catch (e) {
            }
            throw new SyntaxError("parseJSON");
        };
    })(String.prototype);
}

function getScrollTop(){
	var yScrolltop;
	var xScrollleft;
	if (self.pageYOffset || self.pageXOffset) {
		yScrolltop = self.pageYOffset;
		xScrollleft = self.pageXOffset;
	} else if (typeof(document.documentElement) != "undefined" && typeof(document.documentElement.scrollTop) != "undefined" || typeof(document.documentElement.scrollLeft) != "undefined" ){	 // Explorer 6 Strict
		yScrolltop = document.documentElement.scrollTop;
		xScrollleft = document.documentElement.scrollLeft;
	} else if (typeof(document.body) != "undefined") {// all other Explorers
		yScrolltop = document.body.scrollTop;
		xScrollleft = document.body.scrollLeft;
	}

	arrayPageScroll = new Array(xScrollleft,yScrolltop);
	return arrayPageScroll;
}

function getPageSize(){
	var de = document.documentElement;
	var w = window.innerWidth || self.innerWidth || (de&&de.clientWidth) || document.body.clientWidth;
	var h = window.innerHeight || self.innerHeight || (de&&de.clientHeight) || document.body.clientHeight;
	arrayPageSize = new Array(w,h);
	return arrayPageSize;
}

function findPosX(obj){var curleft = 0;if (obj && obj.offsetParent) {while (obj.offsetParent) {	curleft += obj.offsetLeft;obj = obj.offsetParent;}} else if (obj && obj.x) curleft += obj.x;return curleft;}
function findPosY(obj){var curtop = 0;if (obj && obj.offsetParent) {	while (obj.offsetParent) {	curtop += obj.offsetTop;obj = obj.offsetParent;}} else if (obj && obj.y) curtop += obj.y;return curtop;}

var ajaxFinish = true;
function ajaxLoadingStart()
{
	ajaxFinish = false;
	$("#ajax_loading").show();
}

function ajaxLoadingFinish()
{
	ajaxFinish = true;
	$("#ajax_loading").fadeOut("slow");
}

function registerTableCheckbox(table,className)
{
	$("."+className,$("#"+table)).click
	(
		function()
		{
			if($(this.parentNode.parentNode).attr("className") == "select")
			{
				$(this.parentNode.parentNode).attr("className","");
			}
			else
			{
				$(this.parentNode.parentNode).attr("className","select");
			}
		}
	);
	
	$("tr",$("#"+table)).mouseover
	(
		function()
		{
			if($("."+className,$(this)).attr("checked") != true && $(this).attr("className") != "heading")
			{
				$(this).attr("className","hover");
			}
		}
	);
	
	$("tr",$("#"+table)).mouseout
	(
		function()
		{
			if($("."+className,$(this)).attr("checked") != true && $(this).attr("className") != "heading")
			{
				$(this).attr("className","");
			}
		}
	);
}

function selectTableAll(table,className)
{
	$("."+className,$("#"+table)).each(
		function()
		{
			$(this).attr("checked",true);
			$(this.parentNode.parentNode).attr("className","select");
		}
	);
}

function selectTableNone(table,className)
{
	$("."+className,$("#"+table)).each(
		function()
		{
			$(this).attr("checked",false);
			$(this.parentNode.parentNode).attr("className","");
		}
	);
}

function selectTableOther(table,className)
{
	$("."+className,$("#"+table)).each(
		function()
		{
			if($(this).attr("checked") == true)
			{
				$(this).attr("checked",false);
				$(this.parentNode.parentNode).attr("className","");
			}
			else
			{
				$(this).attr("checked",true);
				$(this.parentNode.parentNode).attr("className","select");
			}
		}
	);
}

function registerAutocomplete(element)
{
	$(element).focus(
		function()
		{
			e = $(document.createElement("ul"));
			e.addClass("magike_autoc");
			e.width($(this).width()+4);
			e.css("left",findPosX(this)+"px");
			e.css("top",findPosY(this) + $(this).height() + 4 +"px");
			e.attr("id",$(this).attr("id")+"_autoc");
			e.insertAfter($(this));
		}
	);
	
	$(element).blur(
		function()
		{
			$("#"+$(this).attr("id")+"_autoc").remove();
		}
	);
	
	$(element).keyup(
		function()
		{
			el = $("#"+$(this).attr("id")+"_autoc");
			el.html("");
			e = $(document.createElement("li"));
			e.html("<strong>M</strong>agike");
			
			e.mouseover(
				function()
				{
					$(this).addClass("hover");
				}
			);
			
			e.mouseout(
				function()
				{
					$(this).removeClass("hover");
				}
			);
			
			e.click(
				function()
				{
					alert('ddd');
					$(element).val(this.nodeValue);
				}
			);
			
			el.append(e);
			e = $(document.createElement("li"));
			e.html("<strong>M</strong>icrosoft");
			el.append(e);
			el.show();
		}
	);
}

function registerInputFocus(element)
{
	$("input",element).focus
	(
		function()
		{
			e = $(this);
			if(e.attr("type") == "text" || e.attr("type") == "password")
			{
				c = e.attr("className");
				e.attr("className",c+" focus");
			}
		}
	);
	
	$("textarea",element).focus
	(
		function()
		{
			e = $(this);
			c = e.attr("className");
			e.attr("className",c+" focus");
		}
	);
	
	$("input",element).blur
	(
		function()
		{
			e = $(this);
			if(e.attr("type") == "text" || e.attr("type") == "password")
			{
				c = e.attr("className");
				e.attr("className",c.replace(" focus",""));
			}
		}
	);
	
	$("textarea",element).blur
	(
		function()
		{
			e = $(this);
			c = e.attr("className");
			e.attr("className",c.replace(" focus",""));
		}
	);
	
	$("span.button",element).mouseover
	(
		function()
		{
			e = $(this);
			e.attr("className",e.attr("className") + " focus");
		}
	);
	
	$("span.button",element).mouseout
	(
		function()
		{
			e = $(this);
			e.attr("className",e.attr("className").replace(" focus",""));
			e.attr("className",e.attr("className").replace(" click",""));
		}
	);
	
	$("span.button",element).mousedown
	(
		function()
		{
			e = $(this);
			e.attr("className",e.attr("className") + " click");
		}
	);
	
	$("span.button",element).mouseup
	(
		function()
		{
			e = $(this);
			e.attr("className",e.attr("className").replace(" click",""));
		}
	);
}

var focusTab;
function registerTab(btn,tab)
{
	$(btn).children("#first").attr("className","focus");
	focusTab = $(btn).children("#first");
	
	$(tab).children().each(
		function()
		{
			$(this).hide();
		}
	);

	$("#"+$(btn).children("#first").attr("rel")).show();
	$(btn).children().each(
		function()
		{
			b = $(document.createElement("b"));
			b.css("border-top","none");
			b.css("border-bottom","none");
			b.css("width",$(this).width() - 4 + "px");
			b.css("margin","0 1px");
			b.css("height","1px");
			b.css("font-size","1px");
			$(this).prepend(b);
			
			b = $(document.createElement("b"));
			b.css("border-top","none");
			b.css("border-left","none");
			b.css("border-right","none");
			b.css("width",$(this).width() - 4 + "px");
			b.css("margin","0 2px");
			$(this).prepend(b);
			
			$(this).click(
				function()
				{
					focusTab.attr("className","");
					$(this).attr("className","focus");
					$("#"+focusTab.attr("rel")).hide();
					$("#"+$(this).attr("rel")).show();
					focusTab = $(this);
				}
			);
			
			$(this).mouseover(
				function()
				{
					$(this).addClass("hover");
				}
			);
			
			$(this).mouseout(
				function()
				{
					$(this).removeClass("hover");
				}
			);
		}
	);
}

var tabBtn;

function tabShow(ele,tab,btn)
{
	$(".tab",$("#"+tab)).hide();
	$("#"+ele,$("#"+tab)).show();

	if(tabBtn)
	{
		tabBtn.attr('className','');
	}
	$(btn).attr('className','focus');
	tabBtn = $(btn);
}

var confirmElement;
function magikeConfirm(el)
{
	confirmElement = el;
	if(confirm($(el).attr("msg")))
	{
		setTimeout("window.location.href = $(confirmElement).attr('rel'); ",0);
	}
}

function fixCssHack()
{
	$("td").each
	(
		function()
		{
			if($(this).html() == "")
			{
				$(this).html("&nbsp;");
			}
		}
	);
	
	$(".message").fadeIn(1000);
	$(".message").click(function(){$(this).hide();});
	$(".proc").click(function(){$(this).hide();});
	$(".validate-word").hide();
}

var validateElements;
var showLoading;
function magikeValidator(url,mod)
{
	validateElements = null;
	$(".validate-word").hide();
	showLoading = true;
	
	if(typeof(tinyMCE) != "undefined")
	{
		tinyMCE.triggerSave();
	}
	
	s = $('.validate-me').serialize();
	$.ajax({
		type: 'POST',
		url: url + '?mod=' + mod,
		data: s,
		success: function(data){
			js = data.parseJSON();
			if(js != 0)
			{
				for(var i in js)
				{
					$("#" + i + "-word").show();
					$("#" + i + "-word").html(js[i]);
				}
				$(".proc").fadeOut();
			}
			else
			{
				validateSuccess.apply(this);
			}
			showLoading = false;
		}
	});
}

$(document).ajaxStart(
	function()
	{
		$(".proc").hide();
		if(showLoading)
		{
			$(".proc").show();
		}
	}
);
