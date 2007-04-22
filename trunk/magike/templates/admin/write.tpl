<[include:header]>
<[include:menu]>

<[module:categories_list]>
<[module:get_current_user]>
<div id="content">
	<div id="element">
	<form method="post" id="write" name="write">
		<div class="tab_nav">
			<ul id="tab">
				<li id="first" rel="write_content"><span>{lang.admin_write.write}</span></li>
				<li rel="write_option"><span>{lang.admin_write.option}</span></li>
				<li rel="write_tools"><span>{lang.admin_write.publish}</span></li>
				<li><span>{lang.admin_write.upload}</span></li>
				<li><span>{lang.admin_write.tools}</span></li>
			</ul>
		</div>
		<div class="tab_content" id="write_tab">
			<div id="write_content">
				<div class="input">
				<h2>{lang.admin_write.title}</h2>
				<p><input type="text" class="text" name="post_title" size=60 /> <br />
				<span class="discribe">{lang.admin_write.title_describe}</span></p>
				</div>
				<div class="input">
					<h2>{lang.admin_write.content}</h2>
					<p>
						<textarea name="post_content" id="post_content" rows="14" style="width:600px"></textarea><br />
						<span class="discribe">{lang.admin_write.content_describe}</span>
					</p>
				</div>
			</div>
			<div id="write_tools">
				<div class="input">
				<h2>文章分类</h2>
				<p>
					<select name="category_id">
					<[loop:$categories_list AS $category]>
						<option value="{$category.id}">{$category.category_name}</option>
					<[/loop]>
					</select> <br />
					<span class="discribe">选择将您的文章发表在哪个分类</span>
				</p>
				</div>
				<div class="input">
				<h2>发布者</h2>
				<p><input type="hidden" name="user_id" value="{$get_current_user.id}" />
					<select name="post_user_name">
						<[if:$get_current_user.user_name]>
						<option value="{$get_current_user.user_name}">{$get_current_user.user_name}</option>
						<[/if]>
						<[if:$get_current_user.user_nick]>
						<option value="{$get_current_user.user_nick}">{$get_current_user.user_nick}</option>
						<[/if]>
						<[if:$get_current_user.user_realname]>
						<option value="{$get_current_user.user_realname}">{$get_current_user.user_realname}</option>
						<[/if]>
					</select> <br />
					<span class="discribe">请为您的一个名称作为文章发布者</span>
				</p>
				</div>
				<div class="input">
				<h2>{lang.admin_write.tag}</h2>
				<p><input type="text" class="text" name="post_tags" size=60 /> <br />
				<span class="discribe">{lang.admin_write.tag_describe}</span></p>
				</div>
				<div class="input">
				<h2>{lang.admin_write.trackback}</h2>
				<p><textarea type="text" class="text" name="trackback" cols=60 rows=5 ></textarea> <br />
				<span class="discribe">{lang.admin_write.trackback_describe}</span></p>
				</div>
			</div>
			<div id="write_option">
				<div class="input">
					<h2>{lang.admin_write.write_type}</h2> 
					<p>
					<input type="checkbox" name="post_is_page" value="1" class="checkbox"/> {lang.admin_write.write_type_page}
					<br />
					<span class="discribe">{lang.admin_write.write_type_describe}</span>
					</p>
				</div>
				<div class="input">
					<h2>{lang.admin_write.write_access}</h2> 
					<p>
					<input type="checkbox" name="post_allow_comment" class="checkbox" value="check" checked = ture/> {lang.admin_write.write_allowcomment} 
					<input type="checkbox" name="post_allow_ping" class="checkbox" value="check" checked = ture/> {lang.admin_write.write_allowtrackback} 
					<input type="checkbox" name="post_allow_feed" class="checkbox" value="check" checked = ture/> {lang.admin_write.write_allowfeed} 
					<input type="checkbox" name="post_is_hidden" class="checkbox" value="check"/> {lang.admin_write.write_hidden} 
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
					<input type="text" name="post_password" class="text" />
					<br />
					<span class="discribe">{lang.admin_write.post_password_describe}</span>
					</p>
				</div>
			</div>
		</div>
		<div style="margin-top:6px;line-height:40px;">
		<input type="button" class="button" style="padding:0;width:100px;height:30px;background:#FF9900;color:#FFF;border:2px solid #DB8400;font-size:11pt;font-weight:bold" value="{lang.admin_write.draft}" />
		<input type="button" class="button" style="padding:0;width:100px;height:30px;background:#003399;color:#FFF;border:2px solid #001A4F;font-size:11pt;font-weight:bold" value="{lang.admin_write.publish}" />
		</div>
	</form>
	</div>
</div>

<script language="javascript" type="text/javascript" src="{$static_var.siteurl}/templates/{$static_var.admin_template}/javascript/tiny_mce/tiny_mce.js"></script>
<script>
registerTab("#tab","#write_tab");
tinyMCE.init({
mode : "exact",
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
</script>

<[include:footer]>