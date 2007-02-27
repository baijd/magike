<section:include content="header"/>
<section:include content="menu"/>

<div id="content">
	<div id="element">
		<div class="tab_nav">
			<ul>
				<li><span class="focus" id="tab_first_button" onclick="tabShow('write_content','write_tab',this);">{lang.admin_write.write}</span></li>
				<li><span onclick="tabShow('write_option','write_tab',this)">{lang.admin_write.option}</span></li>
				<li><span>{lang.admin_write.upload}</span></li>
				<li><span>{lang.admin_write.publish}</span></li>
			</ul>
		</div>
		<div class="tab_content" id="write_tab">
			<div class="tab" id="write_content" style="display:block">
				<div class="input">
				<h2>{lang.admin_write.title}</h2>
				<p><input type="text" class="text" name="title" size=60 /> <br />
				<span class="discribe">{lang.admin_write.title_describe}</span></p>
				</div>
				<div class="input">
					<h2>{lang.admin_write.content}</h2>
					<p>
						<textarea name="post_content" id="post_content" rows="14" style="width:600px"></textarea>
						<span class="discribe">{lang.admin_write.content_describe}</span>
					</p>
				</div>
			</div>
			<div class="tab" id="write_option">
				<div class="input">
					<h2>{lang.admin_write.write_type}</h2> 
					<p>
					<input type="radio" name="post_type" value="archive" class="radio"/> {lang.admin_write.write_type_archive}
					<input type="radio" name="post_type" value="page" class="radio"/> {lang.admin_write.write_type_page}
					<input type="radio" name="post_type" value="announce" class="radio"/> {lang.admin_write.write_type_announce}
					<input type="radio" name="post_type" value="side" class="radio"/> {lang.admin_write.write_type_side}
					<br />
					<span class="discribe">{lang.admin_write.write_type_describe}</span>
					</p>
				</div>
				<div class="input">
					<h2>{lang.admin_write.write_access}</h2> 
					<p>
					<input type="checkbox" name="comment" class="checkbox" value="check" checked = ture/> {lang.admin_write.write_allowcomment} 
					<input type="checkbox" name="ping" class="checkbox" value="check" checked = ture/> {lang.admin_write.write_allowtrackback} 
					<input type="checkbox" name="hidden" class="checkbox" value="check" checked = ture/> {lang.admin_write.write_allowfeed} 
					<input type="checkbox" name="hidden" class="checkbox" value="check"/> {lang.admin_write.write_hidden} 
					<br />
					<span class="discribe">{lang.admin_write.write_access_describe}</span>
					</p>
				</div>
				<div class="input">
					<h2>{lang.admin_write.post_name}</h2> 
					<p>
					<input type="text" name="post_name" class="text" />
					<br />
					<span class="discribe">{lang.admin_write.post_name_describe}</span>
					</p>
				</div>
				<div class="input">
					<h2>{lang.admin_write.post_password}</h2> 
					<p>
					<input type="text" name="post_name" class="text" />
					<br />
					<span class="discribe">{lang.admin_write.post_password_describe}</span>
					</p>
				</div>
			</div>
		</div>
		<div style="margin-top:6px;line-height:40px;">
		<input type="button" class="button" value="{lang.admin_write.draft}" />
		<input type="button" class="button" value="{lang.admin_write.publish}" />
		</div>
	</div>
</div>

<script language="javascript" type="text/javascript" src="{$static_var.siteurl}/templates/{$static_var.admin_template}/javascript/tiny_mce/tiny_mce.js"></script>
<script>
tinyMCE.init({
mode : "textareas",
theme : "advanced",
elements : "post_content",
language :"{$static_var.language}",
plugins : "flash,magike",
theme_advanced_buttons1 : "bold,italic,underline,strikethrough, separator, forecolor ,magike_more,magike_page",
theme_advanced_buttons1_add_before: "undo,redo,help,code,separator,hr,link,unlink,image,flash,separator,bullist,numlist,outdent,indent,justifyleft,justifycenter,justifyright",
theme_advanced_buttons2 :"",
theme_advanced_buttons3 : "",
theme_advanced_toolbar_location : "top",
theme_advanced_toolbar_align : "left",
content_css : "{$static_var.siteurl}/templates/{$static_var.template}/editor.css",
relative_urls : false,
remove_script_host : false
});
tabBtn = $("#tab_first_button");
</script>

<section:include content="footer"/>