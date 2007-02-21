<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="{$static_var.content_type};charset={$static_var.charset}" />
	<title>{lang.login.login_to}{$static_var.blog_name}</title>
	<link href="{$static_var.siteurl}/templates/{$static_var.admin_template}/style.css" rel="stylesheet" type="text/css" />
	<script>
		var templateUrl = "{$static_var.siteurl}/templates/{$static_var.admin_template}";
	</script>
	<script language="javascript" type="text/javascript" src="{$static_var.siteurl}/templates/{$static_var.admin_template}/javascript/jquery.js"></script>
	<script language="javascript" type="text/javascript" src="{$static_var.siteurl}/templates/{$static_var.admin_template}/javascript/magike_control.js"></script>
	<style>
		body
		{
			border-top:4px solid #000;
			background:#FFF url({$static_var.siteurl}/templates/{$static_var.admin_template}/images/top.gif) top repeat-x;
		}
		
		#login
		{
			margin:0 auto;
			width:300px;
			background:#3D434E url({$static_var.siteurl}/templates/{$static_var.admin_template}/images/login.gif) bottom repeat-x;
			bottom:0;
			border:2px solid #BBB;
			border-top:none;
		}
		
		#element h2
		{
			border-bottom:1px solid #DDD;
			color:#DDD;
			text-align:left;
		}
	</style>
</head>
<body>
<section:module content="admin_login"/>
<div id="login">
	<div id="element" style="width:260px;padding:30px;padding-top:0;text-align:center">
	<img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/logo.gif" alt="logo" />
	<section:if content="$admin_login.message_open">
	<div id="message" style="color:#FFF;font-weight:bold;padding:5px;text-align:center;">
		{$admin_login.message}
	</div>
	</section:if>
	<section:if content="$admin_login.login_open">
	<form method="post">
	<h2>{lang.login.user_name}</h2>
	<p><input type="text" name="username" style="width:250px" class="text" /></p>
	<h2>{lang.login.password}</h2>
	<p><input type="password" name="password" style="width:250px" class="text" /></p>
	<p style="margin-top:10px;text-align:right">
	<input type="button" value="{lang.login.login}" onclick="submit();" class="button" />
	<input type="button" value="{lang.login.foget_password}&raquo;" class="button" />
	<input type="hidden" name="do" value="login" /></p>
	</form>
	</section:if>
	</div>
</div>
</body>
</html>