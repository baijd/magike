<[include:header]>


<[module:post]>
<div id="content">
	<div id="side">
	<div id="incontent">
	<div id="sidecontent">
		<h1>{<a href="{$static_var.siteurl}">{$static_var.blog_name}</a>}</h1>
		<div class="entry alt">
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
</div>
<[include:footer]>
