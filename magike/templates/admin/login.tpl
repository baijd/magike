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
			background:#FFF url({$static_var.siteurl}/templates/{$static_var.admin_template}/images/top.gif) top repeat-x;
		}
		
		#login
		{
			margin:0 auto;
			padding:10px 0;
			width:333px;
			height:333px;
			background:url({$static_var.siteurl}/templates/{$static_var.admin_template}/images/loginbg.gif) top no-repeat;
		}
		
		#login_top
		{
			height:70px;
		}
		
		#login_top_nav img
		{
			margin-top:4px;
			margin-bottom:-6px;
		}
		
		#element
		{
			margin:0;
		}

		#element h1
		{
			font-weight:normal;
			font-size:19pt;
			font-family:'trebuchet ms',helvetica,sans-serif;
			color:#fff;
			font-weight:normal;
		}
		
		#element h2
		{
			color:#fff;
			text-align:left;
			padding:0 5px;
			margin:10px 0;
			border-bottom:1px solid #fff;
		}
		#banner
		{
			margin:10px;
			padding:10px 10px 0 10px;
			text-align:center;
		}
	</style>
</head>
<body>
<[module:admin_login]>
<div id="banner">
	<img width="200" src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/logo.jpg" alt="logo" />
</div>
<div id="login">
	<div id="element" style="width:270px;padding:30px;padding-top:0;text-align:center;">
	<h1>{lang.login.login_to}{$static_var.blog_name}</h1>
	<[if:$admin_login.message_open]>
	<div id="message" style="color:#FFF;background:#990000;font-weight:bold;padding:5px;">
		{$admin_login.message}
	</div>
	<br/>
	<[/if]>
	<form method="post">
	<h2>{lang.login.user_name}</h2>
	<p><input type="text" name="username" style="width:260px" class="text" /></p>
	<br/>
	<h2>{lang.login.password}</h2>
	<p><input type="password" name="password" style="width:260px" class="text" /></p>
	<br/>
	<p style="margin-top:10px;text-align:right">
	<input type="button" value="{lang.login.login}" onclick="submit();" class="button" />
	<input type="button" value="{lang.login.foget_password}&raquo;" class="button" />
	<input type="hidden" name="do" value="login" /></p>
	</form>
	</div>
</div>
<script>
	registerInputFocus("#login");
</script>
</body>
</html>