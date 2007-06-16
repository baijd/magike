<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="{$static_var.content_type};charset={$static_var.charset}" />
	<title>{$static_var.admin_title} &raquo; {$static_var.admin_parent_title} &raquo; {$static_var.blog_name}后台页面 - Powered by {$static_var.version}</title>
	<link href="{$static_var.siteurl}/templates/{$static_var.admin_template}/style.css" rel="stylesheet" type="text/css" />
	<script language="javascript" type="text/javascript" src="{$static_var.siteurl}/templates/{$static_var.admin_template}/javascript/jquery.js"></script>
	<script language="javascript" type="text/javascript" src="{$static_var.siteurl}/templates/{$static_var.admin_template}/javascript/magike_control.js"></script>
</head>
<body>
<div id="top">
<div id="title">
<span style="float:left">
<img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/user.gif" alt="user" />欢迎回来 <a href="{$static_var.index}/admin/users/user/?user_id={$access.user_id}">{$access.user_name}</a> | <a href="{$static_var.index}/admin/logout/">登出</a>
</span>
<span style="float:right">
<img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/home.gif" alt="home" /><a href="{$static_var.siteurl}">返回主页</a>
 | 
<img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/help.gif" alt="help" /><a href="http://www.magike.org">获取帮助</a>
</span>
</div>
</div>
