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
		setTimeout("window.location.href = $(confirmElement).rel(); ",0);
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
	$(".proc").hide();
	$(".validate-word").hide();
}

var validateElements;
function magikeValidator(url,mod)
{
	validateElements = null;
	$(".validate-word").hide();
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
			}
			else
			{
				validateSuccess.apply(this);
			}
		}
	});
}

$(document).ajaxStart(
	function()
	{
		$(".proc").show();
	}
);

$(document).ajaxStop(
	function()
	{
		$(".proc").fadeOut();
	}
);
