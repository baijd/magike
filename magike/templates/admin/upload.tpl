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
	</style>
</head>
<body>

<[module:file_input]>
<div id="content">
	<div id="element" style="margin:0;padding:0">
	<form enctype="multipart/form-data" id="upload" method="post">
	<div class="input">
		<h2>上传文件</h2>
		<p>
				  <input type="file" name="file" size="60" /><br />
			<span class="discribe">(请选择一个文件以供上传)</span>
		</p>
	</div>
	<div class="input">
		<h2>文件描述</h2>
		<p>
				  <input type="text" class="text" size="60" name="file_describe" /><br />
			<span class="discribe">(描述这个文件,这有利于您以后找到它)</span>
		</p>
	</div>
	<div class="input">
		<h2>文件上传</h2>
		<p>
	  			<input type="submit" value="上传" /><input type="hidden" name="do" value="insert" /><br />
			<span class="discribe">(点击这个按钮,上传您刚刚选定的文件)</span>
		</p>
	</div>
	</form>
	<script>
		registerInputFocus("#upload");
	</script>
	</div>
</div>
<script>
	parent.getFilesList(1);
</script>
</body>
</html>
