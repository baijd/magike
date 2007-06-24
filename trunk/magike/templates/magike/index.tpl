<[include:header]>

<[module:posts.is_archive]>
<[module:page_navigator.posts_is_archive]>
<div id="content">
	<div id="side">
	<div id="incontent">
	<div id="sidecontent">
	<h1>{<a href="{$static_var.siteurl}">{$static_var.blog_name}</a>}</h1>
	<[loop:$posts.is_archive AS $post]>
		<div class="entry">
			<h2><a href="{$static_var.index}/archives/{$post.post_id}/">{$post.post_title}</a></h2>
			<div class="entry_date">{{$post.post_time}}</div>
			<div class="entry_content">
			{$post.post_content}
			</div>
			<div class="entry_nav"><a href="{$static_var.index}/archives/{$post.post_id}/#comment">{$post.post_comment_num} Comments</a></div>
		</div>
	<[/loop]>
	<div class="page_nav">
			<[if:$page_navigator.posts_is_archive.next]><a class="right" href="{$static_var.index}/page/{$page_navigator.posts_is_archive.next}/">下一页</a><[/if]>
			<[if:$page_navigator.posts_is_archive.prev]><a class="left" href="{$static_var.index}/page/{$page_navigator.posts_is_archive.prev}/">上一页</a><[/if]>
	</div>
	</div>
	<[include:sidebar]>
	</div>
	</div>
</div>
<[include:footer]>
