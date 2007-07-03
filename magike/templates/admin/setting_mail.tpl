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
	<form method="post" id="setting_other">
		<h2>邮件设置 <span class="discribe">(设置您的邮件选项)</span></h2>
		<div class="input">
			<h2>邮件服务器地址</h2>
			<p>
				<input type="text" class="text validate-me" name="smtp_host" value="{$static_var.smtp_host}" size=60 /><span class="validate-word" id="post_date_format-word"></span><br />
				<span class="discribe">(请设置邮件服务器SMTP的地址)</span>
			</p>
		</div>
		<div class="input">
			<h2>服务端口</h2>
			<p>
				<input type="text" class="text validate-me" name="smtp_port" value="{$static_var.smtp_port}" size=60 /><span class="validate-word" id="post_date_format-word"></span><br />
				<span class="discribe">(请设置SMTP服务器的端口)</span>
			</p>
		</div>
		<div class="input">
			<h2>电子邮箱用户名</h2>
			<p>
				<input type="text" class="text validate-me" name="smtp_user" value="{$static_var.smtp_user}" size=60 /><span class="validate-word" id="post_date_format-word"></span><br />
				<span class="discribe">(请设置电子邮箱的用户名)</span>
			</p>
		</div>
		<div class="input">
			<h2>电子邮箱密码</h2>
			<p>
				<input type="text" class="text validate-me" name="smtp_pass" value="{$static_var.smtp_pass}" size=60 /><span class="validate-word" id="post_date_format-word"></span><br />
				<span class="discribe">(请设置电子邮箱的密码,如果这一项不为空则表示将使用认证方式)</span>
			</p>
		</div>
		<div class="input">
			<h2>使用认证</h2>
			<p>
				<select name="smtp_auth">
					<option value="1" <[if:$static_var.smtp_auth == 1]>selected=true<[/if]>>是</option>
					<option value="0" <[if:$static_var.smtp_auth != 1]>selected=true<[/if]>>否</option>
				</select><br />
				<span class="discribe">(请向您的邮箱提供商了解您的邮箱是否支持这一模式)</span>
			</p>
		</div>
		<div class="input">
			<h2>使用SSL</h2>
			<p>
				<select name="smtp_ssl">
					<option value="1" <[if:$static_var.smtp_ssl == 1]>selected=true<[/if]>>是</option>
					<option value="0" <[if:$static_var.smtp_ssl != 1]>selected=true<[/if]>>否</option>
				</select><br />
				<span class="discribe">(请向您的邮箱提供商了解您的邮箱是否使用这一连接方式)</span>
			</p>
		</div>
		<div class="submit">
			<span class="button" onclick="magikeValidator('{$static_var.index}/helper/validator/','setting_other');">提交信息</span>
			<input type="hidden" name="do" value="update" />
			<script>
				function validateSuccess()
				{
					document.getElementById('setting_other').submit();
				}
			</script>
		</div>
	</form>
	</div>
</div>
<[include:footer]>
