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

//MagikeDbGrid控件
function MagikeDbGrid()
{
	//do nothing
};

MagikeDbGrid.prototype = {
	init: function(getSourceURL,getPageURL,getTitle,keyName,rowNum,parentElement,describeElement,navElement,infoElement,categoryElement)
	{
		this.getSourceURL = getSourceURL;
		this.getPageURL = getPageURL;
		this.title = getTitle;
		this.parent = parentElement;
		this.key = keyName;
		this.rowNum = rowNum;
		this.describe = describeElement;
		this.nav = $("#"+navElement);
		this.category = $("#"+categoryElement);
		this.info = $("#"+infoElement);
		this.nav.hide();

		this.createButton();
		this.createTable(getTitle,rowNum);
		this.getPage(getPageURL);
	},

	createButton: function()
	{
		$("#magike_db_grid_select_all").click(
			function()
			{
				for(var i in magikeDbGrid.source)
				{
					$("input",magikeDbGrid.td[i]["selector"]).attr("checked",true);
					magikeDbGrid.tr[i].attr("className","db_grid_select");
				}
			}
		);

		$("#magike_db_grid_select_none").click(
			function()
			{
				for(var i in magikeDbGrid.source)
				{
					$("input",magikeDbGrid.td[i]["selector"]).attr("checked",false);
					magikeDbGrid.tr[i].attr("className","");
				}
			}
		);

		$("#magike_db_grid_select_other").click(
			function()
			{
				for(var i in magikeDbGrid.source)
				{
					if($("input",magikeDbGrid.td[i]["selector"]).attr("checked"))
					{
						$("input",magikeDbGrid.td[i]["selector"]).attr("checked",false);
						magikeDbGrid.tr[i].attr("className","");
					}
					else
					{
						$("input",magikeDbGrid.td[i]["selector"]).attr("checked",true);
						magikeDbGrid.tr[i].attr("className","db_grid_select");
					}
				}
			}
		);

		if(this.category)
		{
			this.info.append(this.category);
			this.category.hide();
		}

		$("#magike_db_grid_select_category").click(
			function()
			{
				if(magikeDbGrid.info.is(":visible"))
				{
					magikeDbGrid.info.slideUp();
					magikeDbGrid.category.hide();
				}
				else
				{
					magikeDbGrid.info.slideDown();
					magikeDbGrid.category.show();
				}
			}
		);

		if($("#magike_db_grid_select_category_choose"))
		{
			$("#magike_db_grid_select_category_choose").click(
				function()
				{
					val = $("#magike_db_grid_select_category_list").attr("value");
					for(var i in magikeDbGrid.source)
					{
						if(magikeDbGrid.source[i][magikeDbGrid.select] == val)
						{
							$("input",magikeDbGrid.td[i]["selector"]).attr("checked",true);
							magikeDbGrid.tr[i].attr("className","db_grid_select");
						}
					}
				}
			);
		};
	},

	createTable: function(getTitle,rowNum)
	{
		this.table = $(document.createElement("table"));
		this.table.attr("cellSpacing","0");
		this.table.attr("cellPadding","0");
		this.table.attr("border","0");
		this.table.attr("width","100%");
		this.table.attr("id","magike_db_grid");

		this.createRows(getTitle,rowNum);
		$('#'+this.parent).append(this.table);
		if(jQuery.browser.msie)
		{
			this.nav.css("overflow","hidden");
		}
		
		this.info.appendTo($('#'+this.parent));
		this.info.hide();
		this.nav.appendTo($('#'+this.parent));
		this.nav.show();
	},

	createRows: function(getTitle,rowNum)
	{
		//标题项目部分
		tr = $(document.createElement("tr"));
		tr.attr("className","title");

		for(var i in this.title)
		{
			td = $(document.createElement("td"));
			td.html(this.title[i]["text"]);
			if(this.title[i]["width"])
			{
				td.attr("width",this.title[i]["width"]);
			}
			if(this.title[i]["select"])
			{
				this.select = i;
			}

			tr.append(td);
		}

		this.table.append(tr);
		this.tr = new Array();
		this.td = new Array();

		for(i=0;i<rowNum;i++)
		{
			tr = $(document.createElement("tr"));
			tr.attr("index",i);

			this.td[i] = new Array();
			for(var j in this.title)
			{
				td = $(document.createElement("td"));

				if(this.title[j]["class"])
				{
					td.attr("className",this.title[j]["class"]);
				}

				td.html("&nbsp;");
				this.td[i][j] = td;
				tr.append(td);
			}
			this.tr[i] = tr;
			this.table.append(tr);
		}
	},

	updateData: function(source)
	{
		for(i=0;i<this.rowNum;i++)
		{
			for(var j in this.title)
			{
				if(source[i])
				{
					if(j == "selector")
					{
						this.td[i][j].html(null);
						check = $(document.createElement("input"));
						check.attr("type","checkbox");
						check.attr("className","checkbox");
						check.attr("name","id");
						check.attr("value",i);
						check.click(
							function()
							{
								if(magikeDbGrid.tr[$(this).attr("value")].attr("className") != "db_grid_select")
								{
									magikeDbGrid.tr[$(this).attr("value")].attr("className","db_grid_select");
								}
								else
								{
									magikeDbGrid.tr[$(this).attr("value")].attr("className","");
								}
							}
						);
						this.td[i][j].append(check);
					}
					else
					{
						this.td[i][j].html(source[i][j]);
						if(this.title[j]["click"])
						{
							this.td[i][j].css("cursor","pointer");
						}
					}
				}
				else
				{
					this.td[i][j].html("&nbsp;");
					this.td[i][j].css("cursor","default");
				}
			}
		}
		ajaxLoadingFinish();
	},

	getPage: function(getPageURL)
	{
		ajaxLoadingStart();
		$.get(getPageURL,null,this.getPageHandle);
	},

	getSource: function(getSourceURL)
	{
		$.get(getSourceURL,null,this.getSourceHandle);
	},

	getPageHandle: function(s)
	{
		magikeDbGrid.pageInfo = s.parseJSON();
		magikeDbGrid.getSource(magikeDbGrid.getSourceURL);
	},

	getSourceHandle: function(s)
	{
		magikeDbGrid.source = s.parseJSON();
		if(s == "[]")
		{
			magikeDbGrid.source = new Array();
		}
		magikeDbGrid.updateData(magikeDbGrid.source);
	}
};

var MagikeDbGrid = MagikeDbGrid;
var magikeDbGrid = new MagikeDbGrid();
