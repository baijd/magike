<[include:header]>
<[include:menu]>

<[module:static_var_input]>
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
	<form method="post" id="setting_comment">
		<h2>回响设置 <span class="discribe">(和回响相关的设置)</span></h2>
		<div class="input">
			<h2>评论日期格式</h2>
			<p>
				<input type="text" class="text validate-me" name="comment_date_format" value="{$static_var.comment_date_format}" size=60 /><span class="validate-word" id="comment_date_format-word"></span><br />
				<span class="discribe">(请参考PHP日期格式)</span>
			</p>
		</div>
		<div class="input">
			<h2>评论列表数目</h2>
			<p>
				<input type="text" class="text validate-me" name="comment_list_num" value="{$static_var.comment_list_num}" size=30 /><span class="validate-word" id="comment_list_num-word"></span><br />
				<span class="discribe">(评论显示在列表中的数目)</span>
			</p>
		</div>
		<div class="input">
			<h2>E-mail提醒</h2>
			<p>
				<select name="comment_email">
					<option value="1" <[if:$static_var.comment_email == 1]>selected=true<[/if]>>是</option>
					<option value="0" <[if:$static_var.comment_email != 1]>selected=true<[/if]>>否</option>
				</select><br />
				<span class="discribe">(当网站有回响发布时发给撰写者一封邮件)</span>
			</p>
		</div>
		<div class="input">
			<h2>回响审核</h2>
			<p>
				<select name="comment_check">
					<option value="1" <[if:$static_var.comment_check == 1]>selected=true<[/if]>>是</option>
					<option value="0" <[if:$static_var.comment_check != 1]>selected=true<[/if]>>否</option>
				</select><br />
				<span class="discribe">(让管理员审核所有的回响)</span>
			</p>
		</div>
		<div class="input">
			<h2>必须填写电子邮件</h2>
			<p>
				<select name="comment_email_notnull">
					<option value="1" <[if:$static_var.comment_email_notnull == 1]>selected=true<[/if]>>是</option>
					<option value="0" <[if:$static_var.comment_email_notnull != 1]>selected=true<[/if]>>否</option>
				</select><br />
				<span class="discribe">(评论者是否必须填写电子邮件)</span>
			</p>
		</div>
		<div class="input">
			<h2>必须填写个人主页</h2>
			<p>
				<select name="comment_homepage_notnull">
					<option value="1" <[if:$static_var.comment_homepage_notnull == 1]>selected=true<[/if]>>是</option>
					<option value="0" <[if:$static_var.comment_homepage_notnull != 1]>selected=true<[/if]>>否</option>
				</select><br />
				<span class="discribe">(评论者是否必须填写个人主页)</span>
			</p>
		</div>
		<div class="input">
			<h2>采用ajax方式验证</h2>
			<p>
				<select name="comment_ajax_validator">
					<option value="1" <[if:$static_var.comment_ajax_validator == 1]>selected=true<[/if]>>是</option>
					<option value="0" <[if:$static_var.comment_ajax_validator != 1]>selected=true<[/if]>>否</option>
				</select><br />
				<span class="discribe">(启用这一设置需要您当前的风格支持)</span>
			</p>
		</div>
		<div class="submit">
			<span class="button" onclick="magikeValidator('{$static_var.index}/helper/validator/','setting_comment');">提交信息</span>
			<input type="hidden" name="do" value="update" />
			<script>
				function validateSuccess()
				{
					document.getElementById('setting_comment').submit();
				}
			</script>
		</div>
	</form>
	</div>
</div>
<[include:footer]>
