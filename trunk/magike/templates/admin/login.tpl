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
	padding:20px 10px;
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
	font-size:12pt;
	border-bottom:1px solid #CCC;
	color:#444;
	text-align:right;
	font-family:Georgia, Helvetica, sans-serif;
	font-weight:bold;
}

#login p
{
	text-align:right;
	margin:10px 0;
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

#login .button
{
	width:auto;
	padding:5px 10px;
	height:auto;
	border:3px double #AAA;
	font-size:11pt;
	font-weight:bold;
	background:#F4F4F4;
	height:36px;
	width:100px;
	cursor: pointer;
}

#login a
{
	color:#444;
	font-size:11pt;
	font-weight:bold;
	font-family:Georgia, Helvetica, sans-serif;
}

#login form
{
	padding:0;
	margin:0;
}

#message
{
	background:#990000;
	padding:5px 10px;
	color:#FFF;
	height:24px;
	line-height:24px;
	font-weight:bold;
}
</style>
</head>
[module:static]
[module:admin_login]
<body>
<div id="login">
	<h1><img src="{$static.siteurl}/templates/{$static.admin_template}/images/logo.gif" alt="logo" />{lang.login.login_to}{$static.blog_name}</h1>
	[if $admin_login.message_open == true]
	<div id="message">
		{$admin_login.message}
	</div>
	[/if]
	[if $admin_login.login_open == true]
	<form method="post">
	<h2><img src="{$static.siteurl}/templates/{$static.admin_template}/images/user.gif" alt="user" style="margin-bottom:-2px" /> {lang.login.user_name}</h2>
	<p><input type="text" name="username" /></p>
	<h2><img src="{$static.siteurl}/templates/{$static.admin_template}/images/textfield_key.gif" alt="user" style="margin-bottom:-2px" /> {lang.login.password}</h2>
	<p><input type="password" name="password" /></p>
	<p><input type="button" value="{lang.login.login}" onclick="submit();" class="button" /><input type="hidden" name="do" value="login" /></p>
	<p><a href="#">{lang.login.foget_password}?</a> | <a href="#">{lang.login.register}</a></p>
	</form>
	[/if]
</div>
</body>
</html>