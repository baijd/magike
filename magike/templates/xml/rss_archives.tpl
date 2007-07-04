<?xml version="1.0" encoding="{$static_var.charset}"?>
<rss version="2.0">
<channel>
<[module:get_webmaster]>
<[module:post?time_format=r]>
<[module:comments_list?time_format=r&sort=ASC]>
<[module:http_header?content_type=text/xml]>
<title><![CDATA[{$static_var.blog_name}]]></title>
<link>{$static_var.siteurl}</link>
<description>{$static_var.describe}</description>
<language>zh-cn-utf8</language>
<docs>http://blogs.law.harvard.edu/tech/rss</docs>
<generator>{$static_var.version}</generator>
<webMaster>{$get_webmaster.user_name}</webMaster>
<item>
<title><![CDATA[{$post.post_title}]]></title>
<link>{$static_var.index}/archives/{$post.post_id}/</link>
<author>{$post.post_user_name}</author>
<pubDate>{$post.post_time}</pubDate>
<description><![CDATA[{$post.post_content}]]></description>
</item>
<[loop:$comments_list AS $comment]>
<item>
<title><![CDATA[by {$comment.comment_user}:]]></title>
<link>{$static_var.index}/archives/{$post.post_id}/</link>
<author>{$comment.comment_user}</author>
<pubDate>{$comment.comment_date}</pubDate>
<description><![CDATA[{$comment.comment_text}]]></description>
</item>
<[/loop]>
</channel>
</rss>