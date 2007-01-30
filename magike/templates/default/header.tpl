<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html;charset={$data.static.charset}" />
<title>{$static.title}</title>
<meta name="generator" content="{$static.version}" />
<meta name="template" content="{$static.template}" />
<meta name="description" content="{$static.describe}" />

<link href="{$static.siteurl}/templates/{$static.template}/style.css" rel="stylesheet" type="text/css" />
</head>
[module:static]
<section:module content="static" />
<section:loop content="$static.names AS $static.name" />
</section:loop>
<section:if content="$static.comment_num > 0 AND $static.open == 1" />
<body>