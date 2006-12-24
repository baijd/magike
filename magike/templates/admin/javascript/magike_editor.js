/********************************************
	Magike Project
	copyright (c) Magike Group
	This software must be only used in Magike Systeam.
	http://www.magike.net
	powered by qining
********************************************/

/*********** Magike Editor Begin ************/
var magikeEditor;

function createMagikeEditor(style)
{
	magikeEditor = new MagikeEditor(style);
}

//定义一个基类
function MagikeEditor(style)
{
    this.style = style;
    this.initialize();
}

//初始化对象
MagikeEditor.prototype.initialize = function()
{
   	window.setTimeout("magikeEditor.onloaded();",0);
}

MagikeEditor.prototype.onloaded = function()
{
	this.textElement = $('magike_editor');
	this.doc = this.textElement.contentWindow.document;
	
	this.doc.contentEditable = true;
	this.doc.open();
    this.doc.write('<html>'+
    '<head xmlns="http://www.w3.org/1999/xhtml">'+
    '<title>blank_page</title>'+
    '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">'+
    '</head>'+
    '<body class="magikeEditorContent">'+
    '</body>'+
    '</html>');
    this.doc.close();
    this.doc.designMode = 'On';
    
    window.setTimeout("magikeEditor.setCssProperty();",0);
}

//设置当前的css属性
MagikeEditor.prototype.setCssProperty = function()
{
	if (typeof(this.doc.createStyleSheet) == "undefined") 
	{
		var element = this.doc.createElement("link");

		element.rel = "stylesheet";
		element.href = this.style;

		if ((headArr = this.doc.getElementsByTagName("head")) != null && headArr.length > 0)
			headArr[0].appendChild(element);
	} 
	else
	{
		var styleSheet = this.doc.createStyleSheet(this.style);
	}
}