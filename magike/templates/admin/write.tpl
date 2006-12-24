[include:header]
[include:menu]

<div id="content">
	<div id="element">
		<h2>{lang.admin_write.title} <span class="discribe">{lang.admin_write.title_describe}</span></h2>
		<p><input type="text" name="title" /></p>
		<h2>{lang.admin_write.content} <span class="discribe">{lang.admin_write.content_describe}</span></h2>
		<script language="javascript" type="text/javascript" src="{$static.siteurl}/templates/{$static.admin_template}/javascript/magike_editor.js"></script>
		<p>
			<div id="magike_editor_elements">
				<a href="#" class="magike_editor_element"><img src="{$static.siteurl}/templates/{$static.admin_template}/images/text_indent.gif" /></a>
			</div>
			<iframe id="magike_editor" name="content" frameborder=0 ></iframe>
		</p>
		<script>
			createMagikeEditor('{$static.siteurl}/templates/{$static.template}/editor.css');
		</script>
	</div>
</div>

[include:footer]