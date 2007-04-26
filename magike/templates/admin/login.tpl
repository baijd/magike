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
			border-top:4px solid #222;
			background:#DDD;
		}
		
		#login
		{
			margin:0 auto;
			width:330px;
			padding-top:40px;
		}
		
		#login_top
		{
			height:70px;
		}
		
		#login_top_nav
		{
			margin:0 auto;
			height:40px;
			width:260px;
			background:#000;
			border:1px solid #596271;
			border-top:none;
			line-height:40px;
			color:#EEE;
			font-size:14pt;
		}
		
		#login_top_nav img
		{
			margin-top:4px;
			margin-bottom:-6px;
		}
		
		#element h1
		{
			font-weight:normal;
			font-size:30pt;
		}
		
		#element h2
		{
			border-bottom:1px solid #999;
			color:#222;
			text-align:left;
			padding:0 5px;
			margin:10px 0;
		}
	</style>
</head>
<body>
<[module:admin_login]>
<div id="login">
	<div id="element" style="width:270px;padding:30px;padding-top:0;text-align:center;">
	<img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/logo.gif" alt="logo"/>
	<[if:$admin_login.message_open]>
	<div id="message" style="color:#FFF;font-weight:bold;padding:5px;">
		{$admin_login.message}
	</div>
	<[/if]>
	<[if:$admin_login.login_open]>
	<form method="post">
	<h2>{lang.login.user_name}</h2>
	<p><input type="text" name="username" style="width:260px" class="text" /></p>
	<h2>{lang.login.password}</h2>
	<p><input type="password" name="password" style="width:260px" class="text" /></p>
	<p style="margin-top:10px;text-align:right">
	<input type="button" value="{lang.login.login}" onclick="submit();" class="button" />
	<input type="button" value="{lang.login.foget_password}&raquo;" class="button" />
	<input type="hidden" name="do" value="login" /></p>
	</form>
	<[/if]>
	</div>
</div>
<script>
	registerInputFocus("#login");
</script>
</body>
</html>