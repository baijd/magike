<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="{$static.content_type};charset={$static.charset}" />
	<title>Magike后台页面</title>
	<link href="{$static.siteurl}/templates/{$static.admin_template}/style.css" rel="stylesheet" type="text/css" />
</head>
[module:static]
<body>
<div id="top">
<div id="title">
Magike.Net后台管理
</div>
</div>
<div id="top_menu">
	<ul>
		<li><a href="#" class="focus">撰写文章</a></li>
		<li><a href="#">我的文章</a></li>
		<li><a href="#">管理评论</a></li>
		<li><a href="#">文件目录</a></li>
	</ul>
</div>
<div id="menu_content"></div>
[module:admin_index]
<div id="content">
	<div id="element">
		<h2>{lang.admin_index.global_runtime}</h2>
		<table width=100% width="100%" border="0" cellspacing="1" cellpadding="0">
			<tr>
				<td width=30%>{lang.admin_index.server_version}</td>
				<td width=70%>{$admin_index.server_version}</td>
			</tr>
			<tr>
				<td>{lang.admin_index.database_version}</td>
				<td>{$admin_index.database_version}</td>
			</tr>
			<tr>
				<td>{lang.admin_index.magike_version}</td>
				<td>{$admin_index.magike_version}</td>
			</tr>
			<tr>
				<td>{lang.admin_index.posts_num}</td>
				<td>{$admin_index.posts_num} {lang.admin_index.posts}</td>
			</tr>
		</table>
	</div>
</div>
</body>
</html>
