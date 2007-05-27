<[include:header]>
<[include:menu]>

<[module:user_input]>
<[module:users_list]>
<div id="content">
	<div id="element">
	<[if:$user_input.open]>
		<div class="message">
			{$user_input.word}
		</div>
	<[/if]>
		<h2>用户列表 <span class="discribe">(这里列出了在您网站注册的所有用户)</span></h2>
		<form method="get" id="all_users">
		<table width="100%" cellpadding="0" cellspacing="0" id="users_list">
			<tr class="heading">
				<td width=5%>&nbsp;</td>
				<td width=15%>用户名</td>
				<td width=30%>用户网站</td>
				<td width=25%>电子邮件</td>
				<td width=15%>最后登录</td>
				<td width=10%>操作</td>
			</tr>
			<[loop:$users_list AS $user]>
			<tr>
				<td><input type="checkbox" class="checkbox_element" name="user_id[]" value="{$user.id}"/</td>
				<td><a href="{$static_var.index}/admin/users/user/?user_id={$user.id}">{$user.user_name}</a></td>
				<td class="describe">{$user.user_url}</td>
				<td class="describe">{$user.user_mail}</td>
				<td>{$user.user_lastvisit}</td>
				<td>
					<a class="img" title="编辑" href="{$static_var.index}/admin/users/user/?user_id={$user.id}"><img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/edit.gif" alt="编辑"/></a> 
					<[if:$user.id != 1]>
					<a class="img" title="删除" href="javascript:;" onclick="magikeConfirm(this);" msg="您确定删除用户 '{$user.user_name}' 吗?" rel="{$static_var.index}/admin/users/users_list/?user_id={$user.id}&do=del"><img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/delete.gif" alt="删除"/></a>
					<[/if]>
				</td>
			</tr>
			<[/loop]>
		</table>
		<input type="hidden" name="do" value="del"/>
		</form>
		<div class="table_nav">
			<span onclick="selectTableAll('users_list','checkbox_element')">{lang.admin_db_grid.select_all}</span><b>,</b>
			<span onclick="selectTableNone('users_list','checkbox_element')">{lang.admin_db_grid.select_none}</span><b>,</b>
			<span onclick="selectTableOther('users_list','checkbox_element')">{lang.admin_db_grid.select_other}</span><b>,</b>
			<span onclick="if(confirm('您确定删除这些用户吗?')) document.getElementById('all_users').submit();">{lang.admin_db_grid.select_delete}</span>
	</div>
</div>

<script>
	registerTableCheckbox("users_list","checkbox_element");
</script>
<[include:footer]>