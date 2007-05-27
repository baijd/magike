<[include:header]>
<[include:menu]>


<[module:post]>
<div id="content">
	<div id="side">
	<div id="sidecontent">
		<div class="entry">
			<h2><a href="{$static_var.index}/{$post.post_name}/">{$post.post_title}</a></h2>
			<div class="entry_date">{$post.post_time}</div>
			<div class="entry_content">
			{$post.post_content}
			</div>
		</div>
	</div>
	<[include:sidebar]>
	</div>
</div>
<[include:footer]>
