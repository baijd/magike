<[include:header]>
<[include:menu]>

<[module:static_var_input]>
<[module:categories_list]>
<div id="content">
	<div id="element">
	<[if:$static_var_input.open]>
		<div class="message">
			{$static_var_input.word}
		</div>
	<[/if]>
		<div class="proc">
			正在处理您的请求
		</div>
	<form method="post" id="setting_write">
		<h2>文章撰写设置 <span class="discribe">(调整您的撰写习惯)</span></h2>
		<div class="input">
			<h2>编辑器行数</h2>
			<p>
				<input type="text" class="text validate-me" name="write_editor_rows" value="{$static_var.write_editor_rows}" size=30 /><span class="validate-word" id="write_editor_rows-word"></span><br />
				<span class="discribe">(撰写文章时编辑器默认行数)</span>
			</p>
		</div>
		<div class="input">
			<h2>编辑器自定义标签</h2>
			<p>
				<input type="text" class="text validate-me" name="write_editor_custom_tags" value="{$static_var.write_editor_custom_tags}" size=60 /><br />
				<span class="discribe">(请将您需要在所见即所得编辑器中被识别的标签填入这里并用逗号隔开,例如:player,code)</span>
			</p>
		</div>
		<div class="input">
			<h2>默认撰写者名称</h2>
			<p>
				<select name="write_default_name">
					<option value="username" <[if:$static_var.write_default_name == "username"]>selected=true<[/if]>>用户名</option>
					<option value="nickname" <[if:$static_var.write_default_name == "nickname"]>selected=true<[/if]>>用户昵称</option>
					<option value="firstname" <[if:$static_var.write_default_name == "firstname"]>selected=true<[/if]>>用户真名(姓在前)</option>
					<option value="lastname" <[if:$static_var.write_default_name == "lastname"]>selected=true<[/if]>>用户真名(名在前)</option>
				</select><br />
				<span class="discribe">(撰写文章时默认显示的作者名)</span>
			</p>
		</div>
		<div class="input">
			<h2>默认发表分类</h2>
			<p>
				<select name="write_default_category">
					<[loop:$categories_list AS $category]>
						<option value="{$category.id}" <[if:$category.id == $static_var.write_default_category]>selected=true<[/if]>>{$category.category_name}</option>
					<[/loop]>
				</select><br />
				<span class="discribe">(撰写文章时默认发表的分类)</span>
			</p>
		</div>
		<div class="input">
			<h2>启用自动保存</h2>
			<p>
				<select name="write_auto_save">
					<option value="1" <[if:$static_var.write_auto_save == 1]>selected=true<[/if]>>是</option>
					<option value="0" <[if:$static_var.write_auto_save != 1]>selected=true<[/if]>>否</option>
				</select><br />
				<span class="discribe">(每隔一段时间自动保存您的文章到草稿)</span>
			</p>
		</div>
		<div class="input">
			<h2>默认允许评论</h2>
			<p>
				<select name="default_allow_comment">
					<option value="1" <[if:$static_var.default_allow_comment == 1]>selected=true<[/if]>>是</option>
					<option value="0" <[if:$static_var.default_allow_comment != 1]>selected=true<[/if]>>否</option>
				</select><br />
				<span class="discribe">(您发表的文章是否默认可以被评论)</span>
			</p>
		</div>
		<div class="input">
			<h2>默认允许引用</h2>
			<p>
				<select name="default_allow_ping">
					<option value="1" <[if:$static_var.default_allow_ping == 1]>selected=true<[/if]>>是</option>
					<option value="0" <[if:$static_var.default_allow_ping != 1]>selected=true<[/if]>>否</option>
				</select><br />
				<span class="discribe">(您发表的文章是否默认可以被引用)</span>
			</p>
		</div>
		<div class="submit">
			<span class="button" onclick="magikeValidator('{$static_var.index}/helper/validator/','setting_write');">提交信息</span>
			<input type="hidden" name="do" value="update" />
			<script>
				function validateSuccess()
				{
					document.getElementById('setting_write').submit();
				}
				
				$('#setting_write').submit(
					function()
					{
						magikeValidator('{$static_var.index}/helper/validator/','setting_write');
						return false;
					}
				);
			</script>
		</div>
	</form>
	</div>
</div>
<[include:footer]>
