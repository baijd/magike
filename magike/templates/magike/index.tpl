<[include:header]>


<[module:posts_list?type=1]>
<[module:posts_list_page_nav?type=1]>
<div id="content">
	<div id="side">
	<div id="incontent">
	<[include:menu]>
	<div id="sidecontent">
	<[loop:$posts_list AS $post]>
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
			<[if:$posts_list_page_nav.next]><a class="right" href="{$static_var.index}/page/{$posts_list_page_nav.next}/">下一页</a><[/if]>
			<[if:$posts_list_page_nav.prev]><a class="left" href="{$static_var.index}/page/{$posts_list_page_nav.prev}/">上一页</a><[/if]>
	</div>
	</div>
	<[include:sidebar]>
	<div style="height:10px;float:left;width:750px;background:#FFF;clear:both"></div>
	</div>
	</div>
</div>
<[include:footer]>
