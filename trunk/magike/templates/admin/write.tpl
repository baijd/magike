[include:header]
[include:menu]

<div id="content">
	<div id="element">
		<h2>{lang.admin_write.title} <span class="discribe">{lang.admin_write.title_describe}</span></h2>
		<p><input type="text" name="title" /></p>
		<h2>{lang.admin_write.content} <span class="discribe">{lang.admin_write.content_describe}</span></h2>
		<p>
			<div class="border">
			<textarea name="post_content" id="post_content" rows="14" style="width:100%"></textarea>
			</div>
		</p>
		<h2>{lang.admin_write.tag} <span class="discribe">{lang.admin_write.tag_describe}</span></h2>
		<p><input type="text" name="title" /></p>
		<h2>{lang.admin_write.trackback} <span class="discribe">{lang.admin_write.trackback_describe}</span></h2>
		<p><input type="text" name="title" /></p>
		<div class="option" id="write_option">
			<p>
			<strong>{lang.admin_write.write_type}:</strong> 
			<input type="radio" name="post_type" value="archive" class="radio"/>{lang.admin_write.write_type_archive}
			<input type="radio" name="post_type" value="page" class="radio"/>{lang.admin_write.write_type_page}
			<input type="radio" name="post_type" value="announce" class="radio"/>{lang.admin_write.write_type_announce}
			<input type="radio" name="post_type" value="side" class="radio"/>{lang.admin_write.write_type_side}
			</p>
			<p>
			<strong>{lang.admin_write.write_access}:</strong> 
			<input type="checkbox" name="comment" class="checkbox" value="check" checked = ture/>{lang.admin_write.write_allowcomment} 
			<input type="checkbox" name="ping" class="checkbox" value="check" checked = ture/>{lang.admin_write.write_allowtrackback} 
			<input type="checkbox" name="hidden" class="checkbox" value="check"/>{lang.admin_write.write_hidden} 
			</p>
		</div>
		<p style="margin-top:10px;text-align:center">
		<input type="button" class="button" id="option_button" value="&laquo;{lang.admin_write.option}" />
		<input type="button" class="button" value="{lang.admin_write.draft}" />
		<input type="button" class="button" value="{lang.admin_write.publish}&raquo;" />
		</p>
	</div>
</div>

<script language="javascript" type="text/javascript" src="{$static.siteurl}/templates/{$static.admin_template}/javascript/tiny_mce/tiny_mce.js"></script>
<script>
tinyMCE.init({
mode : "textareas",
theme : "advanced",
elements : "post_content",
language :"{$static.language}",
plugins : "flash,magike",
theme_advanced_buttons1 : "bold,italic,underline,strikethrough, separator, forecolor ,magike_more,magike_page",
theme_advanced_buttons1_add_before: "undo,redo,help,code,separator,hr,link,unlink,image,flash,separator,bullist,numlist,outdent,indent,justifyleft,justifycenter,justifyright",
theme_advanced_buttons2 :"",
theme_advanced_buttons3 : "",
theme_advanced_toolbar_location : "top",
theme_advanced_toolbar_align : "left",
content_css : "{$static.siteurl}/templates/{$static.template}/editor.css",
relative_urls : false,
remove_script_host : false
});

$("#option_button").click(
	function()
	{
		if($("#write_option").is(":visible"))
		{
			$("#write_option").slideUp();
		}
		else
		{
			$("#write_option").slideDown();
		}
	}
);
</script>

[include:footer]