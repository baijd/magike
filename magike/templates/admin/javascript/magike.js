/********************************************
	Magike Project
	copyright (c) Magike Group
	This software must be only used in Magike Systeam.
	http://www.magike.net
	powered by qining
********************************************/

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

$(document).ready(
	function()
	{
		scrollArray = getScrollTop();
		pageArray = getPageSize();
		ajaxLoading = $(document.createElement("div"));
		ajaxLoading.attr("id","ajax_loading");
		ajaxLoading.html("Loading...");
		ajaxLoadingImg = $(document.createElement("img"));
		ajaxLoadingImg.attr("src",templateUrl + "/images/loading.gif");
		ajaxLoading.append(ajaxLoadingImg);

		ajaxLoading.css("left",scrollArray[0] + pageArray[0]/2 - 60 + "px");
		ajaxLoading.css("top",scrollArray[1] + pageArray[1]/2 - 25 + "px");
		$(document.body).append(ajaxLoading);
	}
);

$(document.body).ready(
	function()
	{
		if(ajaxFinish)
		{
			ajaxLoadingFinish();
		}
	}
)

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

//添加inlinePopup效果
function inlinePopup()
{
	var popupWindow = document.createElement("div");
	popupWindow.setAttribute("id","popup");
	document.body.appendChild(popupWindow);
	$("div #popup").style.height = document.body.offsetHeight + "px";
	$("div #popup").innerHTML = getPopupContent();
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
	var h = window.innerHeight || self.innerHeight || (de&&de.clientHeight) || document.body.clientHeight;
	arrayPageSize = new Array(w,h);
	return arrayPageSize;
}

//二级伸缩菜单效果
function initMenu(inputel) {
    navRoot = $("ul #"+inputel);
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

function MagikeDbGrid()
{
	//do nothing
};

MagikeDbGrid.prototype = {
	init: function(getSourceURL,getPageURL,getTitle,parent,key,nav,category){
		ajaxLoadingStart();
		this.getPage(getPageURL);
		this.getSourceURL = getSourceURL;
		this.title = getTitle;
		this.td = new Array();
		this.tr = new Array();
		this.parent = parent;
		this.key = key;
		this.nav = $("#"+nav);
		this.category = $("#"+category);
		this.nav.hide();
		this.category.hide();
		this.createButton();
	},
	
	createButton: function(){
		$("#magike_db_grid_select_all").click(
			function()
			{
				for(var i in magikeDbGrid.td)
				{
					$("input",magikeDbGrid.td[i]["selector"]).attr("checked",true);
					$("#db_gird_id_" + i).attr("className","db_grid_select");
				}
			}
		);

		$("#magike_db_grid_select_none").click(
			function()
			{
				for(var i in magikeDbGrid.td)
				{
					$("input",magikeDbGrid.td[i]["selector"]).attr("checked",false);
					$("#db_gird_id_" + i).attr("className","");
				}
			}
		);

		$("#magike_db_grid_select_other").click(
			function()
			{
				for(var i in magikeDbGrid.td)
				{
					if($("input",magikeDbGrid.td[i]["selector"]).attr("checked"))
					{
						$("input",magikeDbGrid.td[i]["selector"]).attr("checked",false);
						$("#db_gird_id_" + i).attr("className","");
					}
					else
					{
						$("input",magikeDbGrid.td[i]["selector"]).attr("checked",true);
						$("#db_gird_id_" + i).attr("className","db_grid_select");
					}
				}
			}
		);
		
		if($("#db_table_category"))
		{
			$("#db_table_category").appendTo($(this.nav));
		}

		$("#magike_db_grid_select_category").click(
			function()
			{
				if($("#db_table_category").css("display") == "none")
				{
					$("#db_table_category").fadeIn("fast");
				}
				else
				{
					$("#db_table_category").fadeOut("fast");
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
							$("input",magikeDbGrid.td[magikeDbGrid.source[i][magikeDbGrid.key]]["selector"]).attr("checked",true);
							$("#db_gird_id_" + magikeDbGrid.source[i][magikeDbGrid.key]).attr("className","db_grid_select");
						}
					}
				}
			);
		}
	},

	tableHandle: function(){
		this.createDbTable();
		$('#'+this.parent).append(this.table);
		ajaxLoadingFinish();
		this.nav.appendTo($('#'+this.parent));
		this.nav.show();
	},
	
	createDbTable: function(){
		this.table = $(document.createElement("table"));
		this.table.attr("cellSpacing","0");
		this.table.attr("cellPadding","0");
		this.table.attr("border","0");
		this.table.attr("width","100%");
		this.table.attr("id","magike_db_grid");

		this.createTitle();
		this.createRows();
	},

	createTitle: function(){
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
	},

	createRows:function(){
		for(i=0;i < this.pageInfo["limit"];i++)
		{
			tr = $(document.createElement("tr"));
			if(this.source[i])
			{
				tr.attr("id","db_gird_id_"+this.source[i][this.key]);
				this.td[this.source[i][this.key]] = new Array();
			}

			for(var j in this.title)
			{
				td = $(document.createElement("td"));

				if(j != "selector")
				{
					td.html(this.source[i] ? this.source[i][j] : "&nbsp;");
				}
				else
				{
					if(this.source[i])
					{
						check = $(document.createElement("input"));
						check.attr("type","checkbox");
						check.attr("className","checkbox");
						check.attr("name","id[]");
						check.attr("value",this.source[i][this.key]);
						check.click(
							function()
							{
								if($("#db_gird_id_" + $(this).attr("value")).attr("className") != "db_grid_select")
								{
									$("#db_gird_id_" + $(this).attr("value")).attr("className","db_grid_select");
								}
								else
								{
									$("#db_gird_id_" + $(this).attr("value")).attr("className","");
								}
							}
						);
						td.append(check);
					}
					else
					{
						td.html("&nbsp;");
					}
				}

				if(this.title[j]["click"] && this.source[i])
				{
					td.css("cursor","pointer");
				}

				if(this.title[j]["class"])
				{
					td.attr("className",this.title[j]["class"]);
				}

				if(this.source[i])
				{
					this.td[this.source[i][this.key]][j] = td;
				}
				tr.append(td);
			}
			
			if(this.source[i])
			{
				this.tr[this.source[i][this.key]] = tr;
			}
			this.table.append(tr);
		}
	},

	praseData: function(data){
		if(data)
		{
			for(var i in data)
			{
				for(var j in data[i])
				{
					$this.data[i][j].html(data[i][j]);
				}
			}
		}
	},
	
	clear: function(){
		$("tr",this.table).each(
			function()
			{
				$("td",$(this)).each(
					function()
					{
						$(this).html("");
					}
				)
			}
		);
	},

	getPage: function(getPageURL){
		$.get(getPageURL,null,this.getPageHandle);
	},
	
	getSource: function(getSourceURL){
		$.get(getSourceURL,null,this.getSourceHandle);
	},
	
	getPageHandle: function(s){
		magikeDbGrid.pageInfo = s.parseJSON();
		magikeDbGrid.pageFinish = true;
		if(magikeDbGrid.pageSource)
		{
			magikeDbGrid.tableHandle();
			magikeDbGrid.pageFinish = false;
			magikeDbGrid.pageSource = false;
		}

		magikeDbGrid.getSource(magikeDbGrid.getSourceURL);
	},
	
	getSourceHandle: function(s){
		magikeDbGrid.source = s.parseJSON();
		if(s == "[]")
		{
			magikeDbGrid.source = new Array();
		}

		magikeDbGrid.pageSource = true;
		if(magikeDbGrid.pageFinish)
		{
			magikeDbGrid.tableHandle();
			magikeDbGrid.pageFinish = false;
			magikeDbGrid.pageSource = false;
		}
	}
};

var MagikeDbGrid = MagikeDbGrid;
var magikeDbGrid = new MagikeDbGrid();
