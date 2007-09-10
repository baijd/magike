<[php]>
echo '<?xml version="1.0" encoding="'.$data["static_var"]["charset"].'"?>';
<[/php]>
<rss version="2.0"
xmlns:content="http://purl.org/rss/1.0/modules/content/"
xmlns:wfw="http://wellformedweb.org/CommentAPI/"
xmlns:dc="http://purl.org/dc/elements/1.1/">
<channel>
<[module:get_webmaster]>
<[module:posts.is_feed?limit=10&time_format=r&content=1]>
<[module:http_header?content_type=text/xml]>
<title><![CDATA[{$static_var.blog_name}]]></title>
<link>{$static_var.siteurl}</link>
<description>{$static_var.describe}</description>
<language>zh-cn-utf8</language>
<docs>http://blogs.law.harvard.edu/tech/rss</docs>
<generator>{$static_var.version}</generator>
<webMaster>{$get_webmaster.user_name}</webMaster>
<[loop:$posts.is_feed AS $post]>
<item>
<title><![CDATA[{$post.post_title}]]></title>
<link>{$post.permalink}</link>
<comments>{$post.permalink}#comments</comments>
<category>{$post.category_name}</category>
<guid>{$post.permalink}</guid>
<author>{$post.post_user_name}</author>
<dc:creator>{$post.post_user_name}</dc:creator>
<pubDate>{$post.post_time}</pubDate>
<description><![CDATA[{$post.post_content}]]></description>
</item>
<[/loop]>
</channel>
</rss>