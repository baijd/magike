<[include:header]>
<[include:menu]>


<[module:posts_category_list]>
<[module:posts_category_list_page_nav]>

<[if:$posts_category_list]>
<[assign:$posts_category_list AS $posts]>
<[assign:$posts_category_list_page_nav AS $posts_nav]>
<[/if]>

<[module:posts_tag_list]>
<[module:posts_tag_list_page_nav]>

<[if:$posts_tag_list]>
<[assign:$posts_tag_list AS $posts]>
<[assign:$posts_tag_list_page_nav AS $posts_nav]>
<[/if]>

<div id="content">
	<div id="side">
	<div id="sidecontent">
	<[loop:$posts AS $post]>
		<div class="entry">
			<h2><a href="{$static_var.index}/archives/{$post.post_id}/">{$post.post_title}</a></h2>
			<div class="entry_date">{$post.post_time}</div>
			<div class="entry_content">
			{$post.post_content}
			</div>
			<div class="entry_nav"><a href="{$static_var.index}/archives/{$post.post_id}/#comment">{$post.post_comment_num} Comments</a></div>
		</div>
	<[/loop]>
	<div class="page_nav">
			<[if:$posts_nav.next]><a class="right" href="{$static_var.index}/{$posts_nav.query}/{$posts_nav.next}/">下一页</a><[/if]>
			<[if:$posts_nav.prev]><a class="left" href="{$static_var.index}/{$posts_nav.query}/{$posts_nav.prev}/">上一页</a><[/if]>
	</div>
	</div>
	<[include:sidebar]>
	</div>
</div>
<[include:footer]>
