<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="{$static_var.content_type};charset={$static_var.charset}" />
	<title>{$static_var.admin_title} &raquo; {$static_var.blog_name}后台页面 - Powered by {$static_var.version}</title>
	<link href="{$static_var.siteurl}/templates/{$static_var.admin_template}/style.css" rel="stylesheet" type="text/css" />
	<script language="javascript" type="text/javascript" src="{$static_var.siteurl}/templates/{$static_var.admin_template}/javascript/jquery.js"></script>
	<script language="javascript" type="text/javascript" src="{$static_var.siteurl}/templates/{$static_var.admin_template}/javascript/magike_control.js"></script>
	<style>
		body{background:#FFF;}
		#content{margin-bottom:0 !important;}
		#element .input h2
		{
			font-size:10pt;
			color:#333;
			font-weight:normal;
			padding:5px 10px;
		}
		
		#element .input p
		{
			padding:5px;
		}
		
		#element .input
		{
			background:#EEF0F2;
			height:36px;
			line-height:36px;
		}
		
		#file_content
		{
			height:200px;
			width:100%;
			background:#999;
			display:block;
			padding:0;
			float:left;
		}
		
		.file_element
		{
			float:left;
			padding:5px;
			border:1px solid #777;
			background-color:#EEE;
			margin:5px 0 0 5px;
			width:80px;
			height:80px;
			cursor:pointer;
			overflow:hidden;
		}
		
		.file_hover
		{
			border:1px solid #333;
			background-color:#FFFFAA;
		}
		
		#file_page_nav
		{
			text-align:center;
			margin-top:10px;
			font-size:10pt;
			width:100%;
			padding-top:15px;
			float:left;
			color:#AAA;
		}
		
		#file_page_nav a
		{
			color:#333;
		}
		
		#file_page_nav a:hover
		{
			text-decoration:none;
		}
	</style>
</head>
<body>

<[module:file_input]>
<[module:files_list?limit=12]>
<[module:page_navigator.files_list?limit=12]>
<div id="content">
	<div id="element" style="margin:0;padding:0">
	<div class="tab_nav">
	<ul id="tab">
		<li><span class="focus" id="btn-internet" onclick="$(this).addClass('focus');$('#btn-local').removeClass('focus');$('#tab-local').hide();$('#tab-internet').show();">来自互联网</span></li>
		<li><span id="btn-local" onclick="$('#btn-internet').removeClass('focus');$(this).addClass('focus');$('#tab-internet').hide();$('#tab-local').show();">从本地上传</span></li>
	</ul>
	</div>
	<div id="tab-internet">
	<div class="input" style="border-bottom:none;padding-top:10px">
		<h2>文件地址</h2>
		<p>
				  <input type="text" class="text" size="60" id="internet_file_url" name="internet_file_url" />
		</p>
	</div>
	<div class="input" style="border-bottom:none">
		<h2>文件标题</h2>
		<p>
				  <input type="text" class="text" size="60" name="internet_file_describe" />
		</p>
	</div>
	<div class="input">
		<h2>对齐方式</h2>
		<p>
	  			<select name="internet_align">
					<option value="">无</option>
					<option value="left">左对齐</option>
					<option value="right">右对齐</option>
					<option value="center">环绕</option>
				</select>
		</p>
	</div>
	</div>
	<div id="tab-local" style="display:none">
	<form enctype="multipart/form-data" id="upload" method="post">
	<div class="input" style="border-bottom:none;padding-top:10px">
		<h2>上传文件</h2>
		<p>
				  <input type="file" id="file_name" name="file" />
		</p>
	</div>
	<div class="input" style="border-bottom:none">
		<h2>文件描述</h2>
		<p>
				  <input type="text" class="text" size="60" name="file_describe" />
		</p>
	</div>
	<div class="input">
		<h2>文件上传</h2>
		<p>
	  			<input type="button" onclick="if($('#file_name').val()) document.getElementById('upload').submit();else alert('您必须选择一个文件');" value="上传" /><input type="hidden" name="do" value="insert" />
		</p>
	</div>
	</form>
	</div>
	<div id="file_content">
		<[loop:$files_list AS $file]>
		<div title="{$file.file_describe}" style="background-image:url(<[if:$file.is_image]>{$file.thumbnail_permalink}<[else]>{$static_var.siteurl}/templates/{$static_var.admin_template}/images/plugin.gif<[/if]>); background-position:center; background-repeat:no-repeat;" 
		class="file_element" 
		onmousemove="$(this).addClass('file_hover');" 
		onmouseout="$(this).removeClass('file_hover');" 
		onclick="parent.editorInsertImageIsImage = <[if:$file.is_image]>true<[else]>false<[/if]>;fileInsertImage('{$file.permalink}',this);">
		</div>
		<[/loop]>
	</div>
	<div id="file_page_nav">
		<[if:$page_navigator.files_list.prev]><a href="{$page_navigator.files_list.prev_permalink}"><[/if]>上一页<[if:$page_navigator.files_list.prev]></a><[/if]> | 
		<[if:$page_navigator.files_list.next]><a href="{$page_navigator.files_list.next_permalink}"><[/if]>下一页<[if:$page_navigator.files_list.next]></a><[/if]>
	</div>
	</div>
</div>
<script>
	parent.$('.magikePopupMask').remove();
	parent.$('iframe').fadeIn();
	registerInputFocus("#element");
	function fileInsertImage(url,obj)
	{
		$('#btn-internet').addClass('focus');
		$('#btn-local').removeClass('focus');
		$('#tab-internet').show();
		$('#tab-local').hide();
		$("input[@name=internet_file_url]").val(url);
		$("input[@name=internet_file_describe]").val(obj.title);
	}
</script>
</body>
</html>
