/********************************************
	Magike Project
	copyright (c) Magike Group
	This software must be only used in Magike Systeam.
	http://www.magike.net
	powered by qining
********************************************/

/***** Magike Javascript Framework Begin ****/
//实现$函数,根据id获取元素
function $(id,doc)
{
	myDoc = doc ? doc : document;
	return myDoc.getElementById(id);
}

//实现T函数,根据tag返回元素
function T(tagName,doc)
{
	myDoc = doc ? doc : document;
	return myDoc.getElementsByTagName(tagName);
}

//为一个对象增加一个监听函数
function addEvent(object, name, handle)
{
	if (object.attachEvent)
		object.attachEvent("on" + name, handle);
	else
		object.addEventListener(name, handle, false);
}

//json扩展
if (!Object.prototype.toJSONString) {
    Array.prototype.toJSONString = function () {
        var a = ['['], b, i, l = this.length, v;

        function p(s) {
            if (b) {
                a.push(',');
            }
            a.push(s);
            b = true;
        }

        for (i = 0; i < l; i += 1) {
            v = this[i];
            switch (typeof v) {
            case 'undefined':
            case 'function':
            case 'unknown':
                break;
            case 'object':
                if (v) {
                    if (typeof v.toJSONString === 'function') {
                        p(v.toJSONString());
                    }
                } else {
                    p("null");
                }
                break;
            default:
                p(v.toJSONString());
            }
        }
        a.push(']');
        return a.join('');
    };

    Boolean.prototype.toJSONString = function () {
        return String(this);
    };

    Date.prototype.toJSONString = function () {

        function f(n) {
            return n < 10 ? '0' + n : n;
        }

        return '"' + this.getFullYear() + '-' +
                f(this.getMonth() + 1) + '-' +
                f(this.getDate()) + 'T' +
                f(this.getHours()) + ':' +
                f(this.getMinutes()) + ':' +
                f(this.getSeconds()) + '"';
    };

    Number.prototype.toJSONString = function () {
        return isFinite(this) ? String(this) : "null";
    };

    Object.prototype.toJSONString = function () {
        var a = ['{'], b, i, v;

        function p(s) {
            if (b) {
                a.push(',');
            }
            a.push(i.toJSONString(), ':', s);
            b = true;
        }

        for (i in this) {
            if (this.hasOwnProperty(i)) {
                v = this[i];
                switch (typeof v) {
                case 'undefined':
                case 'function':
                case 'unknown':
                    break;
                case 'object':
                    if (v) {
                        if (typeof v.toJSONString === 'function') {
                            p(v.toJSONString());
                        }
                    } else {
                        p("null");
                    }
                    break;
                default:
                    p(v.toJSONString());
                }
            }
        }
        a.push('}');
        return a.join('');
    };


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

        s.toJSONString = function () {
            if (/["\\\x00-\x1f]/.test(this)) {
                return '"' + this.replace(/([\x00-\x1f\\"])/g, function(a, b) {
                    var c = m[b];
                    if (c) {
                        return c;
                    }
                    c = b.charCodeAt();
                    return '\\u00' +
                        Math.floor(c / 16).toString(16) +
                        (c % 16).toString(16);
                }) + '"';
            }
            return '"' + this + '"';
        };
    })(String.prototype);
}

//添加inlinePopup效果
function inlinePopup()
{
	var popupWindow = document.createElement("div");
	popupWindow.setAttribute("id","popup");
	document.body.appendChild(popupWindow);
	$("popup").style.height = document.body.offsetHeight + "px";
	$("popup").innerHTML = getPopupContent();
}

function closePopup()
{
	document.body.removeChild($("popup"));
}

function getPopupContent()
{
	scrollArray = getScrollTop();
	sizeArray = getPageSize();

	str  = '<div id="popup_content" style="margin:auto;background:#FFF;width:400px;height:400px;margin-top:'+ (scrollArray[1] + sizeArray[1]/4) +'px">';
	str += 'hello world';
	str += '</div>';

	return str;
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
	var h = window.innerHeight || self.innerHeight || (de&&de.clientHeight) || document.body.clientHeight
	arrayPageSize = new Array(w,h)
	return arrayPageSize;
}

//二级伸缩菜单效果
function initMenu(inputel) {
    navRoot = $(inputel);
    if (navRoot) {
      for (i=0; i<navRoot.childNodes.length; i++) {
        node = navRoot.childNodes[i];
        if (node.nodeName=="LI") {
          for (j=0; j<node.childNodes.length; j++)
          {
          	  child = node.childNodes[j];
          	  if(child.nodeName=="A")
          	  {
          	  	   link = j;
          	  }

          	  if(child.nodeName=="UL")
          	  {
          	  	   //Fix Firefox bug
          	  	    now = j;
          	  	    nowlink = link;
		          	node.onmouseover = function()
		          	{
		          		  this.childNodes[nowlink].className = "hover";
		              	  this.childNodes[now].className = "over";
		          	}
		          	node.onmouseout = function()
		          	{
		          		  this.childNodes[nowlink].className = "";
		          	  	  this.childNodes[now].className = "";
		          	}
          	  }
          }
 } } } }
