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

/**输入框架自动完成beta
 * Author:张炼
 * Date:2007-8-17
 **/

//自动完成绑定类
function AutoCompleter(textbox, url , boxclass, selectclass, unselectclass, hoverclass){
    //得到输入框
	this.textbox = $(textbox);
	this.textbox.attr("autocomplete", "off");
	this.list = new Array();
	
	//样式的保存
	this.boxclass = boxclass;
	this.selectclass = selectclass;
	this.unselectclass = unselectclass;
	this.hoverclass = hoverclass;
	
	//关键词选取容器
	this.box = document.createElement("div");
	
	//初始化请求地址
	this.url = url;
	
	
	if(!boxclass) this.box.style.cssText = AutoCompleter.defaultBoxStyle;
	else this.box.className = boxclass;
	
	this.box.style.position = "absolute";
    this.box.innerHTML = "Auto complete: Loading...";
	this.hide();
	this.textbox[0].parentNode.insertBefore(this.box, this.textbox[0]);
	
	
	var _completer = this;
	
	//对操作进行事件绑定
	//按键的时候的事件，主要进行
	this.box.onkeydown = function(e){
	    e = e ? e : event;
		return _completer.keydown(e);
	}
	this.textbox.bind("keydown", this.box.onkeydown);
	this.textbox.bind("keyup", function(e){
		_completer.start(e);
	});
	
	if($.browser.opera) {
		this.textbox.bind("keypress", function(e){return e.keyCode!=13 || this.visible == false});
	}
    if (!this.textbox[0].setSelectionRange && this.textbox[0].createTextRange){  
	    function getcate(){
            this.focus();
            var txb = this;
            var s = txb.scrollTop;
            
            var r = document.selection.createRange();
            _completer.caterange = r.duplicate(); 
            
            var t = txb.createTextRange();
            t.collapse(true);
            t.select();
            
            var j = document.selection.createRange();
            r.setEndPoint("StartToStart",j);
            
            _completer.cateposition = r.text.replace(/\n/g, "").length;
            
            r.collapse(false);
            r.select();
            txb.scrollTop = s;
	    }
	    this.textbox[0].getcate = getcate;
	    //this.textbox.bind("blur", getcate);
    }
	$(document).bind("click", function(e){
        var tf = e.srcElement;
        if(!tf) tf = e.target;
        while(tf && tf != document.documentElement && tf != document.body){
            if(tf == _completer.box || tf == _completer.textbox[0]) return;
            tf = tf.parentNode;
        }
        _completer.hide();
	});
}
AutoCompleter.spliters = ",， 　\n\r.。；;!！";
//得到一个元素在页面上的绝对位置，p为想得到位置的元素，返回结果为Object{left:<int>, top:<int>}，r为是否只求到其定位元素
AutoCompleter.getPos = function(tag, r){
    var p = tag;
    var res = {left:p.offsetLeft, top:p.offsetTop};
    do{
        var s = p.currentStyle ? p.currentStyle : getComputedStyle(p, null);
        
        //如果只求到定位元素
        if(r){
            var position = s.position.toLowerCase();
            if(position == "absolute" || position == "relative") break;
        }
        if(p != tag){
            //加上相对位置
            res.left += p.offsetLeft;
            res.top += p.offsetTop;
            
            if($.browser.msie){
                //加上边框宽度
                var border = parseInt(s.borderTopWidth);
                if(!isNaN(border)) res.top += border;
                border = parseInt(s.borderLeftWidth);
                if(!isNaN(border)) res.left += border;
            }
        }
        p = p.offsetParent;
    }while(p);
    return res;
}
AutoCompleter.defaultBoxStyle = "border:1px solid #369;background:#fff;color:#000;cursor:pointer";
AutoCompleter.defaultUnSelectStyle = "padding:2px 10px";
AutoCompleter.defaultSelectStyle = "padding:2px 10px;background:#B8D6D6;color:#fff";
AutoCompleter.defaultHoverStyle = "padding:2px 10px;background:#B8D6D6";
AutoCompleter.prototype = 
{
    //得到当前光标位置
    getindex: function(){
        if(this.textbox[0].getcate){
            this.textbox[0].getcate();
            return this.cateposition;
        }else{
            if(this.textbox[0].setSelectionRange) return this.textbox[0].selectionEnd;
            else return this.textbox.val().length; 
        }   
    },
    
    setindex: function(i){
        if(this.textbox[0].getcate){
            var r = this.textbox[0].createTextRange();
            r.collapse(true);
            r.moveStart('character', i);
            r.select();
        }
        else{
            this.textbox[0].selectionEnd = i;
            this.textbox[0].selectionStart = i;
        }   
    },
    
    //输入框键被按下之后发生
	keydown: function(e){
	    if(e.ctrlKey){
	        if(e.keyCode == 74){
	            e.keyCode = 0;
	            this.start(e);
	        }
	        else return;
	        return false;
	    }
	    if(!this.visible) return;
	    if(e.keyCode == 38) this.up(e);
	    else if(e.keyCode == 40) this.down(e);
	    else if(e.keyCode == 13) {
	        if(!this.word) return;
	        this.select();
	    }
	    else return;
	    return false;
	},
	
	//往上选词
	up: function(e){
	    var last = this.word ? this.list[this.word] : null;
	    if(!last || !last.previousSibling) this.focus(this.box.childNodes[this.box.childNodes.length - 1]); 
	    else this.focus(last.previousSibling);
	},
	
	focus: function(k){
	    var last = this.word ? this.list[this.word] : null;
	    if(last){
	        if(!this.unselectclass) last.style.cssText = AutoCompleter.defaultUnSelectStyle;
	        else last.className = this.unselectclass;
	    }
	    if(k){
	        if(!this.selectclass) k.style.cssText = AutoCompleter.defaultSelectStyle;
	        else k.className = this.selectclass;
	    }
	    this.word = k.innerHTML;
	},
	
	select: function(){
	    this.hide();
	    this.key = this.word;
	    this.lastkey = this.word;
	    
	    if(!this.word) return;
	    
	    var v = this.textbox.val();
	    
	    var r = this.getwordindex(v);
	    
	    var scroll = this.textbox[0].scrollTop;
	    
	    var bstr = v.substr(0, r.start) + this.word;
	    this.textbox.val(bstr + v.substr(r.end, v.length - r.end));
	    this.setindex(bstr.length);
	    
	    this.textbox[0].scrollTop = scroll;
	},
	
	//往下选词
	down: function(e){
	    var last = this.word ? this.list[this.word] : null;
	    if(!last || !last.nextSibling) this.focus(this.box.childNodes[0]); 
	    else this.focus(last.nextSibling);
	},
	
	controlkeys: [38, 40, 13],
	
	//在按键起来之后发生，开始自动完成
	start: function(e){
	    for(var i = 0; i < this.controlkeys.length; ++i){
	        if(e.keyCode == this.controlkeys[i]) return;
	    }
	    this.key = this.getword();
	    //if(this.lastkey == this.key) return;
	    if(this.key == "") this.hide(e);
	    else this.show(e);
	    this.lastkey = this.key;
	},
	
	getword: function(e){
	    var v = this.textbox.val();
	    if( v == "" ) return v;
	    var r = this.getwordindex(v);
	    if(r.end <= r.start) return "";
	    return v.substring(r.start, r.end);
	},
	
	getwordindex: function(v){
	    var ci = this.getindex();
	    var as = AutoCompleter.spliters;
	    var r = {start: -1, end: v.length};
	    for(var i = 0; i < as.length; ++i) r.start = Math.max(v.lastIndexOf(as.charAt(i), ci - 1), r.start);
	    ++r.start;
	    for(var i = 0; i < as.length; ++i){
	        var e = v.indexOf(as.charAt(i), ci);
	        if(e >= 0 && e < r.end) r.end = e;
	    }
	    return r;
	},
	
	//显示自动完成
	show: function(e){
	    if(this.key != this.lastkey)
	        this.search(this.key, e);
	    else this.justshow();
	},
	
	justshow: function(){
		if(!this.length) return;
	    this.visible = true;
	    var p = AutoCompleter.getPos(this.textbox[0]);
	    this.box.style.top = (p.top + this.textbox[0].offsetHeight) + "px";
	    this.box.style.left = p.left + "px";
	    this.box.style.display = "block";
	},
	
	search: function(k, e){
		var _completer = this;
		cacheList = _completer.serachInCache(k);
		
		if(cacheList.length == 0)
		{
		    $.getJSON(this.url + "?keywords=" + k,
			function(json)
			{
				_completer.list = json;
				_completer.reset(json);
			}
			);
		}
		else
		{
			_completer.reset(cacheList);
		}
	},
	
	serachInCache: function(k){
		result = new Array();
		
		for(var i in this.list)
		{
			if(i.toLowerCase().indexOf(k.toLowerCase()) == 0)
			{
				result.push(i);
			}
		}
		
		return result;
	},
	
	reset: function(arr){
		this.length = 0;
	    this.list = {};
	    for(var i = 0; i < arr.length; ++i){
			if(arr[i] == null || arr[i] == "") continue;
	        this.list[arr[i]] = arr[i];
			++ this.length;
	    }
	    this.refresh();
		this.justshow();
	},
	
	refresh: function(){
	    this.word = null;
		
		if(!this.length) this.hide();
	    
	    this.box.innerHTML = "";
	    
	    var _completer = this;
	    for(var k in this.list){
	        if(k == "") continue;
	        var d = document.createElement("div");
	        d.innerHTML = k;
	        this.list[k] = d;
	        this.box.appendChild(d);
	        d.onclick = function(e){
	            _completer.focus(this);
	            _completer.select();
	        }
	        d.onmouseover = function(e){
	            if(this.innerHTML == _completer.word) return;
	            if(_completer.hoverclass) this.className = _completer.hoverclass;
	            else this.style.cssText = AutoCompleter.defaultHoverStyle;
	        }
	        d.onmouseout = function(e){
	            if(this.innerHTML == _completer.word) return;
	            if(!_completer.unselectclass) this.style.cssText = AutoCompleter.defaultUnSelectStyle;
	            else this.className = _completer.unselectclass;
	        }
	        d.onmouseout();
	    }
	},
	
	list: {},
	
	hide: function(e){
	    this.visible = false;
	    this.box.style.display = "none";
	}
}
