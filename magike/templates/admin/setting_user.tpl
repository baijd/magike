<[include:header]>
<[include:menu]>

<[module:static_var_input]>
<[module:groups_list]>
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
	<form method="post" id="setting_user">
		<h2>用户设置 <span class="discribe">(调整用户的相关配置)</span></h2>
		<div class="input">
			<h2>允许用户注册</h2>
			<p>
				<select name="user_allow_register">
					<option value="1" <[if:$static_var.user_allow_register == 1]>selected=true<[/if]>>是</option>
					<option value="0" <[if:$static_var.user_allow_register != 1]>selected=true<[/if]>>否</option>
				</select><br />
				<span class="discribe">(是否允许用户在您的网站注册)</span>
			</p>
		</div>
		<div class="input">
			<h2>默认注册用户组</h2>
			<p>
				<select name="user_register_group">
					<option value="0" <[if:$static_var.user_register_group == "0"]>selected=true<[/if]>>{lang.group.administrator}</option>
					<option value="1" <[if:$static_var.user_register_group == "1"]>selected=true<[/if]>>{lang.group.editor}</option>
					<option value="2" <[if:$static_var.user_register_group == "2"]>selected=true<[/if]>>{lang.group.contributor}</option>
					<option value="3" <[if:$static_var.user_register_group == "3"]>selected=true<[/if]>>{lang.group.visitor}</option>
				</select><br />
				<span class="discribe">(注册后默认的用户组)</span>
			</p>
		</div>
		<div class="submit">
			<span class="button" onclick="magikeValidator('{$static_var.index}/helper/validator/','setting_user');">提交信息</span>
			<input type="hidden" name="do" value="update" />
			<script>
				function validateSuccess()
				{
					document.getElementById('setting_user').submit();
				}
				
				$('#setting_user').submit(
					function()
					{
						magikeValidator('{$static_var.index}/helper/validator/','setting_user');
						return false;
					}
				);
			</script>
		</div>
	</form>
	</div>
</div>
<[include:footer]>
