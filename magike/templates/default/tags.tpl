<[include:header]>

<[module:tags]>
<div id="content">
	<div id="side">
	<div id="incontent">
	<div id="sidecontent">
	<h1>{<a href="{$static_var.siteurl}">{$static_var.blog_name}</a>}</h1>
		<div class="entry alt">
			<h2>标签云</h2>
			<div class="entry_content">
			<[loop:$tags AS $tag]>
				<a href="{$static_var.index}/tags/{$tag.tag_name}/">{$tag.tag_name}[{$tag.tag_count}]</a>
			<[/loop]>
			</div>
		</div>
	</div>
	<[include:sidebar]>
	</div>
	</div>
</div>
<[include:footer]>
