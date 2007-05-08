<[include:header]>
<[include:menu]>

<[module:insert_user]>
<[module:groups_list]>
<div id="content">
	<div id="element">
	<div class="proc">
		正在处理您的请求
	</div>
	<form method="post" id="insert_user" action="{$static_var.index}/admin/users/user_list/?<[if:$insert_user.do == "update"]>user_id={$insert_user.id}&do=update<[/if]><[if:$insert_user.do == "insert"]>do=insert<[/if]>">
	<h2>新增或编辑用户 <span class="discribe">(您可以在此编辑用户的资料)</span></h2>
	<div class="input">
		<h2>用户名</h2>
		<p>
			<input type="text" class="text validate-me" value="{$insert_user.user_name}" name="user_name" size=30 /><span class="validate-word" id="user_name-word"></span><br />
			<span class="discribe">(为此用户分配一个名称,最好使用英文,比如:luxixi)</span>
		</p>
	</div>
	<div class="input">
		<h2>用户密码</h2>
		<p>
			<input type="password" class="text validate-me" name="user_password" size=60 /><br />
			<span class="discribe">(为新注册的用户分配一个密码,如果留空系统将产生一个随机密码)</span>
		</p>
	</div>
	<div class="input">
		<h2>确认密码</h2>
		<p>
			<input type="password" class="text validate-me" name="user_password_confrm" size=60 /><span class="validate-word" id="user_password_confrm-word"></span><br />
			<span class="discribe">(确认您所输入的密码)</span>
		</p>
	</div>
	<div class="input">
		<h2>电子邮箱</h2>
		<p>
			<input type="text" class="text validate-me" value="{$insert_user.user_mail}" name="user_mail" size=60 /><span class="validate-word" id="user_mail-word"></span><br />
			<span class="discribe">(这个用户的电子邮箱,比如:webmaster@magike.net)</span>
		</p>
	</div>
	<div class="input">
		<h2>个人网站</h2>
		<p>
			<input type="text" class="text validate-me" value="{$insert_user.user_url}" name="user_url" size=60 /><span class="validate-word" id="user_url-word"></span><br />
			<span class="discribe">(这个用户的个人网站,比如:http://www.magike.net)</span>
		</p>
	</div>
	<div class="input">
		<h2>真实名称</h2>
		<p>
			<input type="text" class="text" value="{$insert_user.user_realname}" name="user_realname" size=30 /><br />
			<span class="discribe">(这个用户的真实名称,系统不会暴露这个名称)</span>
		</p>
	</div>
	<div class="input">
		<h2>昵称</h2>
		<p>
			<input type="text" class="text" value="{$insert_user.user_nick}" name="user_nick" size=30 /><br />
			<span class="discribe">(这个用户的昵称,比如:鲁西西)</span>
		</p>
	</div>
	<div class="input">
		<h2>相关描述</h2>
		<p>
			<textarea class="text" name="user_about" cols=60 rows=5 >{$insert_user.user_about}</textarea><br />
			<span class="discribe">(对这个用户的描述,比如:他是个很懒的家伙)</span>
		</p>
	</div>
	<div class="input">
		<h2>所属群组</h2>
		<p>
			<[loop:$groups_list AS $group]>
				<input type="checkbox" name="user_group[]" class="checkbox validate-me" value="{$group.id}" <[if:in_array($group.id,$insert_user.user_group)]>checked=true<[/if]> /> {$group.group_name} <span class="discribe">{$group.group_describe}</span><br />
			<[/loop]>
			<span class="validate-word" id="user_group-word"></span><span class="discribe">(为这个用户分配一个用户组)</span>
		</p>
	</div>
	<div class="submit">
		<span class="button" onclick="magikeValidator('{$static_var.index}/helper/validator/','add_user');">提交信息</span>
		<script>
			function validateSuccess()
			{
				document.getElementById('insert_user').submit();
			}
		</script>
	</div>
	</div>
</div>

<[include:footer]>