<[module:recent_posts]>
<[module:recent_comments?striptags=1&substr=10]>
<[module:links_prase_list?limit=7]>
<[module:categories_list]>

<div id="sidebar">
	<h2>Category</h2>
	<ul>
	<[loop:$categories_list AS $category]>
	<li><a href="{$static_var.index}/category/{$category.category_postname}/">{$category.category_name}</a></li>
	<[/loop]>
	</ul>
	<h2>Recent Posts</h2>
	<ul>
	<[loop:$recent_posts AS $post]>
	<li><a href="{$static_var.index}/archives/{$post.post_id}/">{$post.post_title}</a></li>
	<[/loop]>
	</ul>
	<h2>Recent Comments</h2>
	<ul>
	<[loop:$recent_comments AS $comment]>
	<li><a href="{$static_var.index}/archives/{$comment.post_id}/#comment-{$comment.comment_id}">{$comment.comment_user}: {$comment.comment_text}</a></li>
	<[/loop]>
	</ul>
	<[loop:$links_prase_list AS $link_category]>
	<h2>{$link_category.link_category_name}</h2>
	<ul>
	<[loop:$link_category.items AS $link]>
	<li><a href="{$link.link_url}">{$link.link_name}</a></li>
	<[/loop]>
	</ul>
	<[/loop]>
</div>