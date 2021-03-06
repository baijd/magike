<[include:header]>


<[module:posts.fetch_by_category]>
<[module:page_navigator.posts_fetch_by_category]>

<[if:$posts.fetch_by_category]>
<[assign:$posts.fetch_by_category AS $archives]>
<[assign:$page_navigator.posts_fetch_by_category AS $navigator]>
<[/if]>

<[module:posts.fetch_by_tag]>
<[module:page_navigator.posts_fetch_by_tag]>

<[if:$posts.fetch_by_tag]>
<[assign:$posts.fetch_by_tag AS $archives]>
<[assign:$page_navigator.posts_fetch_by_tag AS $navigator]>
<[/if]>

<[module:posts.fetch_by_search]>
<[module:page_navigator.posts_fetch_by_search]>

<[if:$posts.fetch_by_search]>
<[assign:$posts.fetch_by_search AS $archives]>
<[assign:$page_navigator.posts_fetch_by_search AS $navigator]>
<[/if]>

<div id="content">
	<div id="side">
	<div id="incontent">
	<div id="sidecontent">
	<h1>{<a href="{$static_var.siteurl}">{$static_var.blog_name}</a>}</h1>
	<[if:$archives]>
	<[loop:$archives AS $post]>
		<div class="entry <[if:!$post.post_alt]>alt<[/if]>">
			<h2><a href="{$static_var.index}/archives/{$post.post_id}/">{$post.post_title}</a></h2>
			<div class="entry_date">{$post.post_time}</div>
			<div class="entry_content">
			{$post.post_content}
			</div>
			<div class="entry_nav"><a href="{$static_var.index}/archives/{$post.post_id}/#comment">{$post.post_comment_num} Comments</a></div>
		</div>
	<[/loop]>
	<div class="page_nav">
			<[if:$navigator.next]><a class="right" href="{$static_var.index}/{$navigator.query}{$navigator.next}">下一页</a><[/if]>
			<[if:$navigator.prev]><a class="left" href="{$static_var.index}/{$navigator.query}{$navigator.prev}">上一页</a><[/if]>
	</div>
	<[else]>
	<div class="entry alt">
		<h2>没有任何内容</h2>
	</div>
	<[/if]>
	</div>
	<[include:sidebar]>
	</div>
	</div>
</div>
<[include:footer]>
