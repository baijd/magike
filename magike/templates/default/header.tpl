<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="{$static_var.content_type};charset={$static_var.charset}" />
<title><[if:$static_var.blog_title]>{$static_var.blog_title}<[else]>{$static_var.blog_name}<[/if]> - {$static_var.describe}</title>
<meta name="generator" content="{$static_var.version}" />
<meta name="template" content="{$static_var.template}" />
<meta name="description" content="{$static_var.describe}" />
<meta name="keywords" content="{$static_var.keywords}" />
<[if:$post.post_id]>
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="{$static_var.index}/rss/archives/{$post.post_id}/" />
<[else]>
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="{$static_var.index}/rss/" />
<[/if]>
<link rel="EditURI" type="application/rsd+xml" title="RSD" href="{$static_var.index}/xmlrpc.api?rsd" />
<link rel="stylesheet" href="{$static_var.siteurl}/templates/{$static_var.template}/style.css" type="text/css" media="screen" />
</head>
<body>
<[include:menu]>

