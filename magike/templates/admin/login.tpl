<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="{$static.content_type};charset={$static.charset}" />
	<title>{lang.login.login_to}{$static.blog_name}</title>
	<link href="{$static.siteurl}/templates/{$static.admin_template}/style.css" rel="stylesheet" type="text/css" />
	<script>
		var templateUrl = "{$static.siteurl}/templates/{$static.admin_template}";
	</script>
	<script language="javascript" type="text/javascript" src="{$static.siteurl}/templates/{$static.admin_template}/javascript/jquery.js"></script>
	<script language="javascript" type="text/javascript" src="{$static.siteurl}/templates/{$static.admin_template}/javascript/magike_control.js"></script>
</head>
[module:static_module]
<body>
<div id="top" style="border-bottom:1px solid #666;">
<div id="title" style="background:url({$static.siteurl}/templates/{$static.admin_template}/images/separator.gif) bottom repeat-x;width:auto">
<img src="{$static.siteurl}/data/images/logos.gif" alt="logo" /> {lang.login.login_to}{$static.blog_name}
</div>
</div>
[module:admin_login_module]
<div id="content" style="background:#FFF url({$static.siteurl}/templates/{$static.admin_template}/images/top.gif) top repeat-x;">
	<div id="element" style="width:260px;background:#F3F1E9;border:1px solid #999;padding:30px;">
	[if $admin_login.message_open == true]
	<div id="message" style="background:#AA0000;color:#FFF;font-weight:bold;padding:5px;text-align:center;border:1px solid #444">
		{$admin_login.message}
	</div>
	[/if]
	[if $admin_login.login_open == true]
	<form method="post">
	<h2><img src="{$static.siteurl}/templates/{$static.admin_template}/images/user.gif" alt="user" style="margin-bottom:-2px;" /> {lang.login.user_name}</h2>
	<p><input type="text" name="username" style="width:250px" /></p>
	<h2><img src="{$static.siteurl}/templates/{$static.admin_template}/images/textfield_key.gif" alt="user" style="margin-bottom:-2px" /> {lang.login.password}</h2>
	<p><input type="password" name="password" style="width:250px" /></p>
	<p style="margin-top:10px;text-align:right">
	<input type="button" value="{lang.login.login}" onclick="submit();" class="button" />
	<input type="button" value="{lang.login.foget_password}&raquo;" class="button" />
	<input type="hidden" name="do" value="login" /></p>
	</form>
	[/if]
	</div>
</div>
</body>
</html>