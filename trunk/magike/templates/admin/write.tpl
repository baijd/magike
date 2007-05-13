<[include:header]>
<[include:menu]>

<[module:categories_list]>
<[module:get_current_user]>
<[module:write_post]>

<script language="javascript" type="text/javascript" src="{$static_var.siteurl}/{!__TEMPLATE__}/{$static_var.admin_template}/javascript/interface.js"></script>
<style>
#files_grid
{
	margin:0 0 10px 10px !important;
	margin:10px 0 10px 10px;
	padding:0;
	width:550px !important;
	width:560px;
	height:150px;
	background:#EEF0F2;
	border:1px solid #BEC9D1;
	float:left;
}

#files_grid li
{
	list-style:none;
	float:left;
	width:88px;
	height:128px;
	padding:5px;
	border:1px solid #BEC9D1;
	background:#FFF;
	margin:5px;
	cursor:pointer;
}

#sidebar input
{
	border:1px solid #555;
	background:#FFF;
	height:20px;
	line-height:20px;
}

#files_grid li.hover
{
	background:#FFFFAA;
	border:1px solid #999;
}
</style>

<div id="content">
	<div id="element">
		<div id="sidebar">
			您选定了
			<strong id="sidebar_word"></strong>&nbsp;
			<input type="button" value="插入" onclick="$('#first').trigger('click');setTimeout('insertContent();',0);"/> 
			<input type="button" value="删除"/> 
			<input type="button" value="取消" onclick="$('#sidebar').hide();" />
		</div>
		<div class="proc">
			正在处理您的请求
		</div>
	<form method="post" id="write" action="{$static_var.index}/admin/posts/all/?do={$write_post.do}<[if:$write_post.do == "update"]>&post_id={$write_post.post_id}<[/if]>">
		<div class="tab_nav">
			<ul id="tab">
				<li id="first" rel="write_content"><span>{lang.admin_write.write}</span></li>
				<li rel="write_option"><span>{lang.admin_write.option}</span></li>
				<li rel="write_tools"><span>{lang.admin_write.publish}</span></li>
				<li rel="write_upload"><span>{lang.admin_write.upload}</span></li>
				<li><span>{lang.admin_write.tools}</span></li>
			</ul>
		</div>
		<div class="tab_content" id="write_tab">
			<div id="write_content">
				<div class="input">
				<h2>{lang.admin_write.title}</h2>
				<p><input type="text" class="text validate-me" name="post_title" size=60 value="{$write_post.post_title}" /><span class="validate-word" id="post_title-word"></span> <br />
				<span class="discribe">{lang.admin_write.title_describe}</span></p>
				</div>
				<div class="input">
					<h2>{lang.admin_write.content}</h2>
					<p>
						<textarea name="post_content" rows="14" class="validate-me" style="background:url({$static_var.siteurl}/{!__TEMPLATE__}/{$static_var.admin_template}/images/editor_loading.gif) center no-repeat;width:600px">{$write_post.post_content}</textarea><br />
						<span class="validate-word" id="post_content-word"></span>&nbsp;
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
						<option value="{$category.id}" <[if:$category.id == $write_post.category_id]>selected=true<[/if]>>{$category.category_name}</option>
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
						<option value="{$get_current_user.user_name}" <[if:$get_current_user.user_name == $write_post.post_user_name]>selected=true<[/if]>>{$get_current_user.user_name}</option>
						<[/if]>
						<[if:$get_current_user.user_nick]>
						<option value="{$get_current_user.user_nick}" <[if:$get_current_user.user_nick == $write_post.post_user_name]>selected=true<[/if]>>{$get_current_user.user_nick}</option>
						<[/if]>
						<[if:$get_current_user.user_realname]>
						<option value="{$get_current_user.user_realname}" <[if:$get_current_user.user_realname == $write_post.post_user_name]>selected=true<[/if]>>{$get_current_user.user_realname}</option>
						<[/if]>
					</select> <br />
					<span class="discribe">请为您的一个名称作为文章发布者</span>
				</p>
				</div>
				<div class="input">
				<h2>{lang.admin_write.tag}</h2>
				<p><input type="text" class="text" name="post_tags" value="{$write_post.post_tags}" size=60 /> <br />
				<span class="discribe">{lang.admin_write.tag_describe}</span></p>
				</div>
				<div class="input">
				<h2>{lang.admin_write.trackback}</h2>
				<p>
				<textarea class="text" name="post_trackback" cols=60 rows=5 ></textarea> <br />
				<span class="discribe">{lang.admin_write.trackback_describe}</span></p>
				</div>
			</div>
			<div id="write_upload">
				<iframe frameborder=0 width=100% height=200 src="{$static_var.index}/admin/posts/upload/"></iframe>
				<div class="input" style="padding-bottom:0 !important;padding-bottom:10px;margin-bottom:0 !important;margin-bottom:6px;">
					<h2>文件列表</h2>
						<ul id="files_grid">
						</ul>
				</div>
			</div>
			<div id="write_option">
				<div class="input">
					<h2>{lang.admin_write.write_type}</h2> 
					<p>
					<input type="checkbox" name="post_is_page" value="1" <[if:$write_post.post_is_page]>checked=true<[/if]> class="checkbox"/> {lang.admin_write.write_type_page}
					<br />
					<span class="discribe">{lang.admin_write.write_type_describe}</span>
					</p>
				</div>
				<div class="input">
					<h2>{lang.admin_write.write_access}</h2> 
					<p>
					<input type="checkbox" name="post_allow_comment" class="checkbox" value="1" <[if:$write_post.post_allow_comment]>checked=true<[/if]>/> {lang.admin_write.write_allowcomment} 
					<input type="checkbox" name="post_allow_ping" class="checkbox" value="1" <[if:$write_post.post_allow_ping]>checked=true<[/if]>/> {lang.admin_write.write_allowtrackback} 
					<input type="checkbox" name="post_allow_feed" class="checkbox" value="1" <[if:$write_post.post_allow_feed]>checked=true<[/if]>/> {lang.admin_write.write_allowfeed} 
					<input type="checkbox" name="post_is_hidden" class="checkbox" value="1" <[if:$write_post.post_is_hidden]>checked=true<[/if]>/> {lang.admin_write.write_hidden} 
					<br />
					<span class="discribe">{lang.admin_write.write_access_describe}</span>
					</p>
				</div>
				<div class="input">
					<h2>{lang.admin_write.post_name}</h2> 
					<p>
					<input type="text" name="post_name" class="text"  value="{$write_post.post_name}" />
					<br />
					<span class="discribe">{lang.admin_write.post_name_describe}</span>
					</p>
				</div>
				<div class="input">
					<h2>{lang.admin_write.post_password}</h2> 
					<p>
					<input type="text" name="post_password" class="text"  value="{$write_post.post_password}" />
					<br />
					<span class="discribe">{lang.admin_write.post_password_describe}</span>
					</p>
				</div>
			</div>
		</div>
		<div style="margin-top:6px;line-height:40px;">
		<input type="button" class="button" onclick="$('#post_is_draft').val(1);magikeValidator('{$static_var.index}/helper/validator/','write_post');" style="cursor:pointer;padding:0;width:100px;height:30px;background:#FF9900;color:#FFF;border:2px solid #DB8400;font-size:11pt;font-weight:bold" value="{lang.admin_write.draft}" />
		<input type="button" class="button" onclick="$('#post_is_draft').val(0);magikeValidator('{$static_var.index}/helper/validator/','write_post');" style="cursor:pointer;padding:0;width:100px;height:30px;background:#003399;color:#FFF;border:2px solid #001A4F;font-size:11pt;font-weight:bold" value="{lang.admin_write.publish}" />
		<input type="hidden" name="post_is_draft" id="post_is_draft" value="0" />
		</div>
	</form>
	</div>
</div>

<script>
registerTab("#tab","#write_tab");
function validateSuccess()
{
	document.getElementById('write').submit();
}

$.getScript("{$static_var.siteurl}/{!__TEMPLATE__}/{$static_var.admin_template}/javascript/tiny_mce/tiny_mce.js", function(){
 tinyMCE.scriptUrl = "{$static_var.siteurl}/{!__TEMPLATE__}/{$static_var.admin_template}/javascript/tiny_mce/tiny_mce.js";
 tinyMCE.init({
	mode : "exact",
	theme : "advanced",
	elements : "post_content",
	language :"{$static_var.language}",
	plugins : "flash,magike,inlinepopups",
	theme_advanced_buttons1 : "bold,italic,underline,strikethrough, separator, forecolor ,magike_more,magike_page",
	theme_advanced_buttons1_add_before: "undo,redo,code,separator,hr,link,unlink,image,flash,separator,bullist,numlist,outdent,indent,justifyleft,justifycenter,justifyright",
	theme_advanced_buttons2 :"",
	theme_advanced_buttons3 : "",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	content_css : "{$static_var.siteurl}/{!__TEMPLATE__}/{$static_var.admin_template}/editor.css",
	relative_urls : false,
	remove_script_host : false
	});
	
 window.setTimeout("showEditor();",500);
 });

function showEditor()
{
	if(!$(".mceEditor").html())
	{
		tinyMCE.execCommand('mceAddControl', false, 'post_content');
	}
}

function insertContent()
{
	if($("#sidebar").attr("type") == "true")
	{
		tinyMCE.execCommand('mceInsertContent',true,'<img src=' + $("#sidebar").attr("rel") + ' alt=' + $("#sidebar").attr("alt") + ' />');
	}
	else
	{
		tinyMCE.execCommand('mceInsertContent',true,'<a href=' + $("#sidebar").attr("rel") + ' title=' + $("#sidebar").attr("alt") + ' >'+$("#sidebar").attr("alt")+'</a>');
	}
}

function getFilesList(page)
{
	showLoading = true;
	$("#files_grid").html("");
	$.getJSON("{$static_var.index}/admin/posts/files_list/?page="+page,
				function(json)
				{
					for(var i in json)
					{
					if(typeof(json[i]["id"]) != "undefined")
					{
						li = $(document.createElement("li"));
						src = "{$static_var.index}/res/" + json[i]["id"]+"/"+json[i]["file_name"];
						li.attr("className","normal");
						li.attr("rel",src);
						li.attr("alt",json[i]["file_describe"] ? json[i]["file_describe"] : json[i]["file_name"]);
						li.attr("type",json[i]["is_image"]);
						img = $(document.createElement("img"));
						if(json[i]["is_image"])
						{
							img.attr("src",src);
						}
						else
						{
							img.attr("src","{$static_var.siteurl}/{!__TEMPLATE__}/{$static_var.admin_template}/images/file_default.gif");
						}
						img.attr("width",88);
						img.attr("alt",json[i]["file_describe"]);
						p = $(document.createElement("p"));
						p.css("font-size","8pt");
						p.css("padding","5px");
						p.html(li.attr("alt"));
						li.append(img);
						li.append(p);
						$("#files_grid").append(li);
						li.hover(
							function()
							{
								$(this).attr("className",$(this).attr("className")+" hover");
							},
							function()
							{
								$(this).attr("className",$(this).attr("className").replace(" hover",""));
							}
						);
						li.click(
							function()
							{
								$("#sidebar_word").html($(this).attr("rel"));
								$("#sidebar").attr("rel",$(this).attr("rel"));
								$("#sidebar").attr("alt",$(this).attr("alt"));
								$("#sidebar").attr("type",$(this).attr("type"));
								
								if($("#sidebar").css("display") == "none")
								{
									$("#sidebar").slideDown();
								}
							}
						);
					}
					}
					showLoading = false;
					$(".proc").hide();
				});
}
</script>

<[include:footer]>