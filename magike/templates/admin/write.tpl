<[include:header]>
<[include:menu]>

<[module:categories_list]>
<[module:get_current_user]>
<[module:write_post]>

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

<[php]>
$data['custom_tags'] = $data['static_var']['write_editor_custom_tags'] ? explode(",",$data['static_var']['write_editor_custom_tags']) : array();
<[/php]>

<div id="content">
	<div id="element">
		<div class="sidebar">
			您选定了
			<strong id="sidebar_word"></strong>&nbsp;
			<input type="button" id="insert-button" value="{lang.admin_write.insert_image}" onclick="$('#first').trigger('click');setTimeout('insertContent();',0);"/> 
			<input type="button" id="delete-button" value="{lang.admin_write.delete_image}" onclick="deleteFile();"/> 
			<input type="button" value="{lang.admin_write.cancel_image}" onclick="$('.sidebar').hide();" />
		</div>
		<div class="proc">
			正在处理您的请求
		</div>
	<form method="post" id="write" action="{$static_var.index}/admin/posts/all/?do={$write_post.do}<[if:$write_post.do == "update"]>&post_id={$write_post.post_id}<[/if]>">
		<div class="tab_content" id="write_tab">
			<div id="write_content" class="tab_element first">
				<p style="margin-bottom:10px;font-size:11pt;line-height:26px"><input type="text" onfocus="this.select();" class="validate-me text" name="post_title" size=60 value="<[if:$write_post.post_title]>{$write_post.post_title}<[else]>无标题文档<[/if]>" />
				</p>
					<div class="toolbar">
					<button type="button" accesskey="b" onclick="editorAdd('<strong>','</strong>');"><strong>B</strong></button>
					<button type="button" accesskey="i" onclick="editorAdd('<em>','</em>');"><em>i</em></button>
					<button type="button" accesskey="d" onclick="editorAdd('<del>','</del>');"><del>del</del></button>
					<button type="button" accesskey="ctrl+b" onclick="editorAdd('<blockquote>','</blockquote>');">blockquote</button>
					<button type="button" accesskey="u" onclick="editorAdd('<ul>','</ul>');">ul</button>
					<button type="button" accesskey="o" onclick="editorAdd('<ol>','</ol>');">ol</button>
					<button type="button" accesskey="l" onclick="editorAdd('<li>','</li>');">li</button>
					<button type="button" accesskey="a" style="color:#0000EE;text-decoration: underline;" onclick="editorInsertLink('插入链接','链接地址:','链接标题:','打开方式:','确定','取消');">link</button>
					<button type="button" accesskey="ctrl+i" onclick="editorInsertImage('插入文件','{$static_var.index}/admin/posts/upload/','确定','取消');">img</button>
					<button type="button" accesskey="c" onclick="editorAdd('<code>','</code>');">code</button>
					<button type="button" accesskey="m" onclick="editorAdd('<!--more-->','');">more</button>
					<[loop:$custom_tags AS $custom_tag]>
					<button type="button" onclick="editorAdd('<{$custom_tag}>','</{$custom_tag}>');">{$custom_tag}</button>
					<[/loop]>
					<button type="button" style="color:#990000">Preferences</button>
					</div>
						<textarea name="post_content" id="post_content" class="validate-me textarea" style="width:710px;height:<[php]>echo $data['static_var']['write_editor_rows']*18<[/php]>px">{$write_post.post_content}</textarea>
						<span class="validate-word" id="post_content-word"></span>
					<h2 style="margin:15px 0 0 0;width:710px">文章分类<span class="discribe">(选择将您的文章发表在哪个分类)</span></h2>
					<p style="margin:5px">
						<select name="category_id" class="text">
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
						</select>
					</p>
					<h2 style="margin:15px 0 0 0;width:710px">{lang.admin_write.tag}<span class="discribe">({lang.admin_write.tag_describe})</span></h2>
					<p style="margin:5px">
						<input type="text" class="text" id="post_tags" name="post_tags" value="{$write_post.post_tags}" style="width:706px" />
					</p>
			</div>
			<div id="write_option" class="tab_element">
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
				<h2>{lang.admin_write.trackback}</h2>
				<p>
				<textarea class="text" name="post_trackback" cols=60 rows=5 ></textarea> <br />
				<span class="discribe">({lang.admin_write.trackback_describe})</span></p>
				</div>
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
		<div style="float:left;height:40px;padding-top:5px;width:720px">
		<span class="button" id="draft_button" onclick="unloadConfirm = true;$('#post_is_draft').val(1);document.getElementById('write').submit();">{lang.admin_write.draft}</span>
		<span class="button" onclick="unloadConfirm = true;$('#post_is_draft').val(0);magikeValidator('{$static_var.index}/helper/validator/','write_post');">{lang.admin_write.publish}</span>
		<input type="hidden" name="post_is_draft" id="post_is_draft" value="0" /><input type="hidden" id="post_id" name="post_id" class="validate-me" value="{$write_post.post_id}" />
		<span class="hit_message"></span>
		</div>
	</form>
	</div>
</div>

<script language="javascript" type="text/javascript" src="{$static_var.siteurl}/templates/{$static_var.admin_template}/javascript/magike_editor.js"></script>
<script>
initEditor("post_content");
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

new AutoCompleter("#post_tags","{$static_var.index}/admin/posts/tags_search/","auto-completer","auto-completer-hover","auto-completer-blur","auto-completer-hover");

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
						$('#post_id').val(insertId);
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