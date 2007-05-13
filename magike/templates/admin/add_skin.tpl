<[include:header]>
<[include:menu]>

<[module:skin_file_input]>
<[module:get_skin_file]>
<[module:skin_files_list]>

<script language="javascript" type="text/javascript" src="{$static_var.siteurl}/{!__TEMPLATE__}/{$static_var.admin_template}/javascript/interface.js"></script>
<style>
#element h2
{
	border-bottom:none;
}
#element-left
{
	float:left;
	width:250px;
	margin-right:-250px;
}

#element-right
{
	margin:0 0 0 265px;
	width:auto;
}

#element-right .textarea
{
	border:1px solid #CCC;
	background:#EEE;
	padding:10px;
}

#element ul
{
	margin:0;
	padding:0;
}

#element ul li
{
	list-style:none;
	margin:0;
	padding:0 10px;
	background:#EEF0F2;
	border-bottom:#BEC9D1 solid 1px;
    	border-top:#FFF solid 1px;
	height:28px;
	line-height:28px;
	font-size:9pt;
	cursor:move;
}

#element ul li a
{
	color:#333;
	font-size:11pt;
}

#element ul li a:hover
{
	text-decoration: none;
}

#element ul li img
{
	margin-right:5px;
	margin-top:7px;
	margin-bottom:-3px;
}

#dragHelper
{
	background:#EEF0F2;
	border-bottom:#BEC9D1 solid 1px;
    border-top:#FFF solid 1px;
	height:28px;
	line-height:28px;
	padding:0 10px;
}

#dragHelper a
{
	color:#333;
}
</style>

<div id="content">
	<div id="element">
	<div class="proc">
		正在处理您的请求
	</div>
	<[if:$skin_file_input.open]>
		<div class="message">
			{$skin_file_input.word}
		</div>
	<[/if]>
		<div id="element-left">
			<h2 style="border-bottom:#BEC9D1 solid 1px;">风格元素 <span class="discribe">(这是组成当前页面风格元素)</span></h2>
			<ul>
			<[loop:$skin_files_list AS $file]>
				<li rel="{$file.file}" class="html-element"><[if:$file.icon]><img src="{$static_var.siteurl}/{!__TEMPLATE__}/{$static_var.admin_template}/images/elements/{$file.file}.gif" alt="{$file.name}" /><[/if]><a href="{$static_var.index}/admin/skins/skin/?file={$file.file}">{$file.name}</a></li>
			<[/loop]>
			</ul>
		</div>
		<div id="element-right">
			<h2>编辑器 <span class="discribe">(再左侧选择一个元素,以供编辑)</span></h2>
			<form method="post" id="skin_file_form" action="{$static_var.index}/admin/skins/skin/?file={$get_skin_file.file}">
			<div class="textarea">
			<textarea class="text" id="drop" name="file_content" style="width:98%;height:350px;margin:0;">{$get_skin_file.content}</textarea>
			<input type="submit" id="submit" value="提交更改" <[if:!$get_skin_file.writeable]>disabled=disabled<[/if]> style="margin-top:10px;" />
			<input type="hidden" name="do" value="update" />
			<input type="hidden" id="file_value" name="file" value="{$get_skin_file.file}" />
			</form>
			</div>
		</div>
	</div>
</div>
<script>
$('#element-left ul li').Draggable(
	{
		revert:		true,
		zIndex: 	1000,
		ghosting:	true,
		opacity: 	0.7
	}
);

$('#drop').Droppable(
	{
		accept : 'html-element', 
		activeclass: 'focus', 
		hoverclass:	'focus',
		ondrop:	function (drag) 
				{
					showLoading = true;
					$.getJSON("{$static_var.index}/admin/skins/get_skin_file/?show=1&file="+$(drag).attr("rel"),
					function(json){
						$("#drop").val(json['content']);
						$("#file_value").val(json['file']);
						if(json['writeable'])
						{
							$("#submit").removeAttr("disabled");
						}
						else
						{
							$("#submit").attr("disabled","disabled");
						}
						$("#skin_file_form").attr("action","{$static_var.index}/admin/skins/skin/?file="+json['file']);
						
						showLoading = false;
						$(".proc").hide();
					});
				},
		fit: true
	}
);
</script>
<[include:footer]>
