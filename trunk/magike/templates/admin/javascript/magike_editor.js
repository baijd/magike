/********************************************
	Magike Project
	copyright (c) Magike Group
	This software must be only used in Magike Systeam.
	http://www.magike.net
********************************************/   
var start = 0;
var end = 0;
var magikeTextarea;

function initEditor(elid)
{
	magikeTextarea = document.getElementById(elid);
}

function editorAdd(flg1,flg2)
{
getEditorPos();
var pre = magikeTextarea.value.substr(0, start);
var post = magikeTextarea.value.substr(end);
var center = magikeTextarea.value.substr(start,end-start);
magikeTextarea.value = pre + flg1 +center+ flg2+ post;

// Firefox
if(typeof(magikeTextarea.selectionStart) == "number"){
    magikeTextarea.setSelectionRange(start+flg1.length,start+flg1.length);
    magikeTextarea.focus();
}

// IE
if(document.selection){
    var tempMouseFocusPos = start;
    for (var i = 0; i <= start; i ++){
	if (magikeTextarea.value.charAt(i) == ' ')
	    tempMouseFocusPos--;
    }
    if(start == end) {
	magikeTextarea.select();
	var rangeIE = document.selection.createRange();
	rangeIE.move('character',tempMouseFocusPos+flg1.length);
	rangeIE.select();
    } else {
	var rangeIE = document.selection.createRange();
	rangeIE.move('character',tempMouseFocusPos+flg1.length);
	rangeIE.select();
    }
}

return true;
}
    
function getEditorPos()
{
if(typeof(magikeTextarea.selectionStart) == "number")
{
	start = magikeTextarea.selectionStart;
	end = magikeTextarea.selectionEnd;
}
else if(document.selection)
{
	magikeTextarea.focus();
	var range = document.selection.createRange();
	if(range.parentElement().id == magikeTextarea.id)
	{
	// create a selection of the whole magikeTextarea
	var range_all = document.body.createTextRange();
	range_all.moveToElementText(magikeTextarea);
	for (start=0; range_all.compareEndPoints("StartToStart", range) < 0; start++)
	    range_all.moveStart('character', 1);
	for (var i = 0; i <= start; i ++){
		if (magikeTextarea.value.charAt(i) == "\n")
		{
			start++;
		}
	}
	 var range_all = document.body.createTextRange();
	 range_all.moveToElementText(magikeTextarea);
	 for (end = 0; range_all.compareEndPoints('StartToEnd', range) < 0; end ++)
	     range_all.moveStart('character', 1);
	     for (var i = 0; i <= end; i ++){
		 if (magikeTextarea.value.charAt(i) == "\n")
		     end ++;
	     }
	}
    }
}

function editorInsertLink(popupTitle,urlWord,titleWord,openType,okText,cancelText)
{
	div = $(document.createElement("div"));
	
	p = $(document.createElement("p"));
	span = $(document.createElement("span"));
	span.text(urlWord);
	input = $(document.createElement("input"));
	input.addClass("text");
	input.attr("type","text");
	input.attr("name","url");
	input.attr("value","http://");
	p.append(span);
	p.append(input);
	div.append(p);
	
	p = $(document.createElement("p"));
	span = $(document.createElement("span"));
	span.text(titleWord);
	input = $(document.createElement("input"));
	input.addClass("text");
	input.attr("type","text");
	input.attr("name","title");
	p.append(span);
	p.append(input);
	div.append(p);
	
	p = $(document.createElement("p"));
	span = $(document.createElement("span"));
	span.text(openType);
	select = magikeCreateSelect({none:"",_blank:"_blank"});
	select.attr("name","link");
	p.append(span);
	p.append(select);
	div.append(p);
	
	magikeUI.createPopup({title: popupTitle,center: true,width: 400,height: 175,text:div,ok:okText,cancel:cancelText,handle:editorInsertLinkHandle});
}

function editorInsertLinkHandle()
{
	url = $("input[@name=url]",$((this.parentNode).parentNode)).val();
	
	ititle = $("input[@name=title]",$((this.parentNode).parentNode)).val();

	link = $("select[@name=link]",$((this.parentNode).parentNode)).val();
	
	if(url && url != "http://")
	{
		editorAdd('<a href="' + url + '"' + (ititle ? ' title="' + ititle + '"' : '') + (link ? ' target="' + link + '"' : '') + '>','</a>');
		$(((this.parentNode).parentNode).parentNode).remove();
	}
	else
	{
		alert("请输入链接地址!");
	}
}

function editorInsertImage(popupTitle,frameUrl,okText,cancelText)
{
	div = $(document.createElement("div"));
	
	iframe = $(document.createElement("iframe"));
	iframe.attr("frameBorder","no");
	iframe.attr("id","image_frame");
	iframe.attr("width","100%");
	iframe.attr("height","400px");
	iframe.attr("src",frameUrl);
	iframe.hide();
	
	mask = $(document.createElement("div"));
	mask.addClass("magikePopupMask");
	
	div.append(iframe);
	div.append(mask);
	magikeUI.createPopup({title: popupTitle,center: true,block:true,shadow:true,width: 600,height: 460,text:div,ok:okText,cancel:cancelText,handle:editorInsertImageHandle});
}

var editorInsertImageIsImage = true;
function editorInsertImageHandle()
{
	obj = document.getElementById('image_frame').contentWindow.document;
	url = $("input[@name=internet_file_url]",obj).val();
	ititle = $("input[@name=internet_file_describe]",obj).val();
	link = $("select[@name=internet_align]",obj).val();
	
	if(url)
	{
		if(editorInsertImageIsImage)
		{
			editorAdd('<img src="' + url + '"' + (ititle ? ' alt="' + ititle + '"' : '') + (link ? ' align="' + link + '"' : '') + '/>','');
		}
		else
		{
			editorAdd('<a href="' + url + '"' + (ititle ? ' title="' + ititle + '"' : '') + '/>','</a>');
		}
		parent.$('.magikeShadow').remove();
		parent.$('.magikePopup').remove();
	}
	else
	{
		alert("请输入文件地址!");
	}
}