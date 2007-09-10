<[php]>
echo '<?xml version="1.0" encoding="'.$data["static_var"]["charset"].'"?>';
<[/php]>
<rss version="2.0"
xmlns:content="http://purl.org/rss/1.0/modules/content/"
xmlns:wfw="http://wellformedweb.org/CommentAPI/"
xmlns:dc="http://purl.org/dc/elements/1.1/">
<channel>
<[module:get_webmaster]>
<[module:post?time_format=r]>
<[module:comments.fetch_by_post?time_format=r&sort=ASC]>
<[module:http_header?content_type=text/xml]>
<title><![CDATA["{$post.post_title}" 的评论]]></title>
<link>{$static_var.index}/archives/{$post.post_id}/</link>
<description>{$static_var.describe}</description>
<language>zh-cn-utf8</language>
<docs>http://blogs.law.harvard.edu/tech/rss</docs>
<generator>{$static_var.version}</generator>
<webMaster>{$get_webmaster.user_name}</webMaster>
<[loop:$comments.fetch_by_post AS $comment]>
<item>
<title><![CDATA[by {$comment.comment_user}:]]></title>
<link>{$comment.permalink}</link>
<author>{$comment.comment_user}</author>
<pubDate>{$comment.comment_date}</pubDate>
<description><![CDATA[{$comment.comment_text}]]></description>
</item>
<[/loop]>
</channel>
</rss>