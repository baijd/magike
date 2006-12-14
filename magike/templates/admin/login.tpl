<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="{$static.content_type};charset={$static.charset}" />
<title>{lang.login.login_to}{$static.blog_name}</title>
<meta name="generator" content="{$static.version}" />
<meta name="template" content="{$static.template}" />
<meta name="description" content="{$static.describe}" />
<style>
body
{
	background:#3D434E;
	padding:0;
	margin:0;
}

#login
{
	background:#FFF url({$static.siteurl}/templates/{$static.admin_template}/images/login_top.gif) bottom left no-repeat;
	width:400px;
	height:500px;
	margin:40px auto;
	border:2px solid #333;
	padding:10px;
}

#login h1
{
	text-align:center;
	padding:30px 10px;
	font-family:Georgia, Helvetica, sans-serif;
	font-size:25pt;
	font-weight:normal;
}

#login img
{
	margin:0 0 -18px 0;
}

#login h2
{
	margin:0;
	padding:0 5px;
	height:30px;
	line-height:30px;
	font-size:22pt;
	border-bottom:1px solid #CCC;
	color:#000;
	text-align:right;
	font-family:Georgia, Helvetica, sans-serif;
	font-weight:normal;
}

#login p
{
	text-align:right;
}

#login input
{
	border-top:1px solid #333;
	border-left:1px solid #333;
	border-right:1px solid #CCC;
	border-bottom:1px solid #CCC;
	padding:4px;
	height:18px;
	font-family:verdana, Helvetica, sans-serif;
	font-size:10pt;
	width:300px;
	background:#FFF url({$static.siteurl}/templates/{$static.admin_template}/images/input_bg.gif) top repeat-x;
}
</style>
</head>
[module:static]
<body>
<div id="login">
	<h1><img src="{$static.siteurl}/templates/{$static.admin_template}/images/logo.gif" alt="logo" />{lang.login.login_to}{$static.blog_name}</h1>
	<h2>{lang.login.user_name}</h2>
	<p><input type="text" name="username" /></p>
	<h2>{lang.login.password}</h2>
	<p><input type="text" name="password" /></p>
</div>
</body>
</html>