<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="{$static_var.content_type};charset={$static_var.charset}" />
<title><[if:$static_var.blog_title]>{$static_var.blog_title}<[else]>{$static_var.blog_name}<[/if]> - {$static_var.describe}</title>
<meta name="generator" content="{$static_var.version}" />
<meta name="template" content="{$static_var.template}" />
<meta name="description" content="{$static_var.describe}" />
<meta name="keywords" content="{$static_var.keywords}" />
<[module:exception_catcher]>
<link href="{$static_var.siteurl}/templates/{$static_var.template}/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div style="text-align:center;width:400px;margin:10px auto;">
<h1 style="font-size:18pt;background:none;border-bottom:1px solid #AAA;">{$exception_catcher.message}</h1>
<h2 style="font-size:12pt;color:#AAA">{$exception_catcher.data}</h2>
</div>
</body>
</html>