[include:header]
[include:menu]

<script language="javascript" type="text/javascript" src="{$static.siteurl}/data/tiny_mce/tiny_mce.js"></script>
<script>
tinyMCE.init({
mode : "textareas",
theme : "advanced",
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
</script>

<div id="content">
	<div id="element">
		<h2>{lang.admin_write.title}</h2>
		<p><input type="text" name="title" /></p>
		<h2>{lang.admin_write.content}</h2>
		<p><textarea id="post_content" name="post_content" cols="70" rows="20" style="width:100%" ></textarea></p>
	</div>
</div>

[include:footer]