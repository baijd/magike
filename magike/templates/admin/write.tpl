<[include:header]>
<[include:menu]>

<[module:categories_list]>
<[module:get_current_user]>
<[module:write_post]>

<script language="javascript" type="text/javascript" src="{$static_var.siteurl}/templates/{$static_var.admin_template}/javascript/interface.js"></script>
<style>
#files_grid
{
	margin:0 0 0 10px;
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

#files_grid li p
{
	white-space:nowrap;
	word-wrap: break-all;
	overflow:hidden
}

.sidebar input
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
		<div class="sidebar">
			您选定了
			<strong id="sidebar_word"></strong>&nbsp;
			<input type="button" id="insert-button" value="插入" onclick="$('#first').trigger('click');setTimeout('insertContent();',0);"/> 
			<input type="button" id="delete-button" value="删除" onclick="deleteFile();"/> 
			<input type="button" value="取消" onclick="$('.sidebar').hide();" />
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
				<p style="margin-bottom:10px;"><input type="text" onfocus="this.select();" class="validate-me text" name="post_title" size=60 value="<[if:$write_post.post_title]>{$write_post.post_title}<[else]>无标题文档<[/if]>" /><span class="validate-word" id="post_title-word"></span></p>
					<p>
						<textarea name="post_content" rows="{$static_var.write_editor_rows}" class="validate-me" style="background:url({$static_var.siteurl}/templates/{$static_var.admin_template}/images/editor_loading.gif) center no-repeat;width:100%">{$write_post.post_content}</textarea><br />
						<span class="validate-word" id="post_content-word"></span>
					</p>
			</div>
			<div id="write_tools">
				<div class="input">
				<h2>文章分类</h2>
				<p>
					<select name="category_id">
					<[if:$write_post.do == "update"]>
					<[loop:$categories_list AS $category]>
						<option value="{$category.id}" <[if:$category.id == $write_post.category_id]>selected=true<[/if]>>{$category.category_name}</option>
					<[/loop]>
					<[/if]>
					<[if:$write_post.do == "insert"]>
					<[loop:$categories_list AS $category]>
						<option value="{$category.id}" <[if:$category.id == $static_var.write_default_category]>selected=true<[/if]>>{$category.category_name}</option>
					<[/loop]>
					<[/if]>
					</select> <br />
					<span class="discribe">(选择将您的文章发表在哪个分类)</span>
				</p>
				</div>
				<div class="input">
				<h2>发布者</h2>
				<p><input type="hidden" name="user_id" value="{$get_current_user.id}" />
					<select name="post_user_name">
					<[if:$write_post.do == "update"]>
						<[if:$get_current_user.user_name]>
						<option value="{$get_current_user.user_name}" <[if:$get_current_user.user_name == $write_post.post_user_name]>selected=true<[/if]>>{$get_current_user.user_name}</option>
						<[/if]>
						<[if:$get_current_user.user_nick]>
						<option value="{$get_current_user.user_nick}" <[if:$get_current_user.user_nick == $write_post.post_user_name]>selected=true<[/if]>>{$get_current_user.user_nick}</option>
						<[/if]>
						<[if:$get_current_user.user_firstname && $get_current_user.user_lastname]>
						<option value="{$get_current_user.user_firstname} {$get_current_user.user_lastname}" <[if:$get_current_user.user_firstname . " " . $get_current_user.user_lastname == $write_post.post_user_name]>selected=true<[/if]>>{$get_current_user.user_firstname} {$get_current_user.user_lastname}</option>
						<option value="{$get_current_user.user_lastname} {$get_current_user.user_firstname}" <[if:$get_current_user.user_lastname . " " . $get_current_user.user_firstname == $write_post.post_user_name]>selected=true<[/if]>>{$get_current_user.user_lastname} {$get_current_user.user_firstname}</option>
						<[/if]>
					<[/if]>
					<[if:$write_post.do == "insert"]>
						<[if:$get_current_user.user_name]>
						<option value="{$get_current_user.user_name}" <[if:$static_var.write_default_name == "username"]>selected=true<[/if]>>{$get_current_user.user_name}</option>
						<[/if]>
						<[if:$get_current_user.user_nick]>
						<option value="{$get_current_user.user_nick}" <[if:$static_var.write_default_name == "nickname"]>selected=true<[/if]>>{$get_current_user.user_nick}</option>
						<[/if]>
						<[if:$get_current_user.user_firstname && $get_current_user.user_lastname]>
						<option value="{$get_current_user.user_firstname} {$get_current_user.user_lastname}" <[if:$static_var.write_default_name == "firstname"]>selected=true<[/if]>>{$get_current_user.user_firstname} {$get_current_user.user_lastname}</option>
						<option value="{$get_current_user.user_lastname} {$get_current_user.user_firstname}" <[if:$static_var.write_default_name == "lastname"]>selected=true<[/if]>>{$get_current_user.user_lastname} {$get_current_user.user_firstname}</option>
						<[/if]>
					<[/if]>
					</select> <br />
					<span class="discribe">(请为您的一个名称作为文章发布者)</span>
				</p>
				</div>
				<div class="input">
				<h2>{lang.admin_write.tag}</h2>
				<p><input type="text" class="text" id="post_tags" name="post_tags" value="{$write_post.post_tags}" size=60 /> <br />
				<span class="discribe">({lang.admin_write.tag_describe})</span></p>
				</div>
				<div class="input">
				<h2>{lang.admin_write.trackback}</h2>
				<p>
				<textarea class="text" name="post_trackback" cols=60 rows=5 ></textarea> <br />
				<span class="discribe">({lang.admin_write.trackback_describe})</span></p>
				</div>
			</div>
			<div id="write_upload">
				<iframe frameborder=0 width=100% height=200 src="{$static_var.index}/admin/posts/upload/"></iframe>
				<div class="input" style="padding:10px 0;margin-bottom:0 !important;margin-bottom:6px;">
					<h2 style="padding-top:0">文件列表</h2>
						<ul id="files_grid">
						</ul>
						<p style="padding:0 !important;padding:10px 0 0 0;width:35px;"><input type="button" id="next-button" onclick="getFilesList(filePage + 1);" style="border:1px solid #CCC;border-left:none;background:#EEE;width:20px;height:76px;float:left;padding:0;" value="&raquo;"/> <input type="button" id="prev-button" onclick="getFilesList(filePage - 1);" style="border:1px solid #CCC;border-left:none;background:#EEE;width:20px;height:76px;float:left;padding:0;" value="&laquo;"/></p>
				</div>
			</div>
			<div id="write_option">
				<div class="input">
					<h2>{lang.admin_write.write_type}</h2> 
					<p>
					<input type="checkbox" name="post_is_page" value="1" <[if:$write_post.post_is_page]>checked=true<[/if]> class="checkbox"/> {lang.admin_write.write_type_page}
					<br />
					<span class="discribe">({lang.admin_write.write_type_describe})</span>
					</p>
				</div>
				<div class="input">
					<h2>{lang.admin_write.write_access}</h2> 
					<p>
					<[if:$write_post.do == "update"]>
					<input type="checkbox" name="post_allow_comment" class="checkbox" value="1" <[if:$write_post.post_allow_comment]>checked=true<[/if]>/> {lang.admin_write.write_allowcomment} 
					<input type="checkbox" name="post_allow_ping" class="checkbox" value="1" <[if:$write_post.post_allow_ping]>checked=true<[/if]>/> {lang.admin_write.write_allowtrackback} 
					<[/if]>
					<[if:$write_post.do == "insert"]>
					<input type="checkbox" name="post_allow_comment" class="checkbox" value="1" <[if:$static_var.default_allow_comment]>checked=true<[/if]>/> {lang.admin_write.write_allowcomment} 
					<input type="checkbox" name="post_allow_ping" class="checkbox" value="1" <[if:$static_var.default_allow_ping]>checked=true<[/if]>/> {lang.admin_write.write_allowtrackback} 
					<[/if]>
					<input type="checkbox" name="post_allow_feed" class="checkbox" value="1" <[if:$write_post.post_allow_feed]>checked=true<[/if]>/> {lang.admin_write.write_allowfeed} 
					<input type="checkbox" id="post_is_hidden_check" onclick="checkPasswordInput(this);" name="post_is_hidden" class="checkbox" value="1" <[if:$write_post.post_is_hidden]>checked=true<[/if]>/> {lang.admin_write.write_hidden} 
					<br />
					<span class="discribe">({lang.admin_write.write_access_describe})</span>
					</p>
				</div>
				<div class="input">
					<h2>{lang.admin_write.post_name}</h2> 
					<p>
					<input type="text" name="post_name" class="text validate-me"  value="{$write_post.post_name}" /><span class="validate-word" id="post_name-word"></span>
					<br />
					<span class="discribe">({lang.admin_write.post_name_describe})</span>
					</p>
				</div>
				<div class="input" id="post_password_input">
					<h2>{lang.admin_write.post_password}</h2> 
					<p>
					<input type="text" name="post_password" class="text"  value="{$write_post.post_password}" />
					<br />
					<span class="discribe">({lang.admin_write.post_password_describe})</span>
					</p>
				</div>
			</div>
		</div>
		<div style="float:left;height:40px;padding-top:5px;">
		<span class="button" id="draft_button" onclick="unloadConfirm = true;$('#post_is_draft').val(1);document.getElementById('write').submit();">{lang.admin_write.draft}</span>
		<span class="button" onclick="unloadConfirm = true;$('#post_is_draft').val(0);magikeValidator('{$static_var.index}/helper/validator/','write_post');">{lang.admin_write.publish}</span>
		<input type="hidden" name="post_is_draft" id="post_is_draft" value="0" /><input type="hidden" name="post_id" class="validate-me" value="{$write_post.post_id}" />
		<span class="hit_message"></span>
		</div>
	</form>
	</div>
</div>

<script language="javascript" type="text/javascript" src="{$static_var.siteurl}/templates/{$static_var.admin_template}/javascript/tiny_mce/tiny_mce.js"></script>
<script>
registerTab("#tab","#write_tab");
function validateSuccess()
{
	document.getElementById('write').submit();
}

function checkPasswordInput(ele)
{
	if($(ele).attr("checked") == true)
	{
		$("#post_password_input").show();
	}
	else
	{
		$("input[@name=post_password]").val("");
		$("#post_password_input").hide();
	}
}

checkPasswordInput("#post_is_hidden_check");

function insertContent()
{
	tinyMCE.execCommand('mceFocus',false,'post_content');
	if($(".sidebar").attr("type") == "true")
	{
		tinyMCE.execCommand('mceInsertContent',true,'<img src=' + $(".sidebar").attr("rel") + ' alt=' + $(".sidebar").attr("alt") + ' />');
	}
	else
	{
		tinyMCE.execCommand('mceInsertContent',true,'<a href=' + $(".sidebar").attr("rel") + ' title=' + $(".sidebar").attr("alt") + ' >'+$(".sidebar").attr("alt")+'</a>');
	}
}

var filePage;

function getFilesList(page)
{
	showLoading = true;
	$("#files_grid").html("");
	
	filePage = page;
	
	$.getJSON("{$static_var.index}/admin/posts/write/files_list_page_nav/?page="+page,
	function(json)
	{
		if(json["next"] == 0)
		{
			$("#next-button").attr("disabled","disabled");
		}
		else
		{
			$("#next-button").removeAttr("disabled");
		}
		
		if(json["prev"] == 0)
		{
			$("#prev-button").attr("disabled","disabled");
		}
		else
		{
			$("#prev-button").removeAttr("disabled");
		}
	});
	
	$.getJSON("{$static_var.index}/admin/posts/files_list/?page="+page,
				function(json)
				{
					for(var i in json)
					{
					if(typeof(json[i]["id"]) != "undefined")
					{
						li = $(document.createElement("li"));
						src = "{$static_var.index}/thumb/" + json[i]["id"]+"/"+json[i]["file_name"];
						li.attr("className","normal");
						li.attr("rel",src);
						li.attr("alt",json[i]["file_describe"] ? json[i]["file_describe"] : json[i]["file_name"]);
						li.attr("type",json[i]["is_image"]);
						li.attr("id",json[i]["id"]);
						img = $(document.createElement("img"));
						if(json[i]["is_image"])
						{
							img.attr("src",src);
						}
						else
						{
							img.attr("src","{$static_var.siteurl}/templates/{$static_var.admin_template}/images/file_default.gif");
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
								$("#sidebar_word").html($(this).attr("rel").replace('/thumb/','/res/'));
								$(".sidebar").attr("rel",$(this).attr("rel").replace('/thumb/','/res/'));
								$(".sidebar").attr("alt",$(this).attr("alt"));
								$(".sidebar").attr("type",$(this).attr("type"));
								$(".sidebar").attr("id",$(this).attr("id"));
						  		$("#insert-button").removeAttr("disabled");
						  		$("#delete-button").removeAttr("disabled");
								
								if($(".sidebar").css("display") == "none")
								{
									$(".sidebar").slideDown();
								}
							}
						);
					}
					}
					showLoading = false;
					$(".proc").fadeOut();
				});
}

function deleteFile()
{
	showLoading = true;
	$.getJSON("{$static_var.index}/admin/posts/write/delete_file/?do=del&file_id="+$(".sidebar").attr("id"),
			  function(json)
			  {
			  	if(json['open'])
			  	{
			  		$("#sidebar_word").html(json['word']);
			  		$("#insert-button").attr("disabled","disabled");
			  		$("#delete-button").attr("disabled","disabled");
			  	}
			  	getFilesList(1);
			  });
}


var draftChange = 0;

<[if:$static_var.write_auto_save]>
window.setInterval('refreshDraftButton();',1000);
var draftButtonText = $('#draft_button').html();
var draftTitle = $('title').html();
var draftDelayTime = 60;
<[if:$write_post.do == "insert"]>
var insertId = 0;
<[/if]>
<[if:$write_post.do == "update"]>
var insertId = {$write_post.post_id};
<[/if]>
var checkedValue;
var formAction;
var hasPost = false;

function getCheckedValue()
{
	checkedValue = '';
	$('input[@type=checkbox]').each(
		function()
		{
			if($(this).attr('checked') == true)
			{
				checkedValue += '&'+$(this).attr('name')+'='+$(this).val();
			}
		}
	);
}

function refreshDraftButton()
{
	if(typeof(tinyMCE) != 'undefined' && tinyMCE.undoIndex != draftChange)
	{
		if(draftDelayTime <= 0)
		{
			$('#draft_button').html(draftButtonText);
			if(!hasPost)
			{
				hasPost = true;
				formAction = '{$static_var.index}/admin/posts/auto_save/?'+(insertId ? 'do=update&post_id='+insertId : 'do=insert');
				getCheckedValue();
				$('.hit_message').html('正在自动保存...');
				s = $('select').serialize()+'&'+$('input.text').serialize()
				+checkedValue+'&post_is_draft=1&post_content='+encodeURIComponent(tinyMCE.getContent());
				$.ajax({
					type: 'POST',
					url:formAction,
					data: s,
					success: function(data){
						js = data.parseJSON();
						insertId = insertId ? insertId : js['insert_id'];
						$('#write').attr('action','{$static_var.index}/admin/posts/all/?do=update&post_id='+insertId);
						$('.hit_message').html('上次保存发生在'+js['time']);
						window.document.title = draftTitle;
						draftDelayTime = 60;
						draftChange = tinyMCE.undoIndex;
						hasPost = false;
					}
				});
			}
		}
		else
		{
			window.document.title = draftTitle + ' *';
			$('#draft_button').html('[' + draftDelayTime + ']' + draftButtonText);
			draftDelayTime--;
		}
	}
}
<[/if]>

var unloadConfirm = false;
window.onbeforeunload=function()
{
	if(typeof(tinyMCE) != 'undefined' && tinyMCE.undoIndex != draftChange)
	{
		if(!unloadConfirm)
		{
			return '您的草稿未保存,确定要离开吗?';
		}
	}
}

$('a').click(
	function()
	{
		if(typeof(tinyMCE) != 'undefined' && tinyMCE.undoIndex != draftChange)
		{
			unloadConfirm = true;
			return confirm('您的草稿未保存,确定要离开吗?');
		}
		else
		{
			return true;
		}
	}
);
</script>

<[include:footer]>
<script>
 tinyMCE.init({
	mode : "exact",
	theme : "advanced",
	elements : "post_content",
	language :"{$static_var.language}",
	plugins : "flash,magike,inlinepopups",
	theme_advanced_buttons1 : "bold,italic,underline,strikethrough, separator, forecolor ,magike_more",
	theme_advanced_buttons1_add_before: "undo,redo,code,separator,hr,link,unlink,image,flash,separator,bullist,numlist,outdent,indent,justifyleft,justifycenter,justifyright",
	theme_advanced_buttons2 :"",
	theme_advanced_buttons3 : "",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	content_css : "{$static_var.siteurl}/templates/{$static_var.admin_template}/editor.css",
	relative_urls : false,
	remove_script_host : false,
	extended_valid_elements : "{$static_var.write_editor_custom_tags}"
	});
</script>