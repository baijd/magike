<[include:header]>
<[include:menu]>

<[module:groups_list]>
<div id="content">
	<div id="element">
		<div class="proc">
			正在处理您的请求
		</div>
	<form method="post" id="insert_group" action="{$static_var.index}/admin/users/groups_list/?<[if:$insert_group.do == "update"]>group_id={$insert_group.id}&do=update<[/if]><[if:$insert_group.do == "insert"]>do=insert<[/if]>">
		<h2>增加或编辑路径 <span class="discribe">(您可以在这里操作虚拟路径)</span></h2>
		<div class="input">
			<h2>路径描述名称</h2>
			<p>
				<input type="text" class="text validate-me" name="group_name" value="{$insert_group.group_name}" size=60 /><span class="validate-word" id="group_name-word"></span><br />
				<span class="discribe">(为您的用户组分配一个名称,比如:管理员)</span>
			</p>
		</div>
		<div class="input">
			<h2>路径地址</h2>
			<p>
				<input type="text" class="text validate-me" name="group_describe" value="{$insert_group.group_describe}" size=60 /><br />
				<span class="discribe">(描述这个用户组,如果没有必要,可以留空)</span>
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
			<span class="button" onclick="magikeValidator('{$static_var.index}/helper/validator/','add_group');">提交信息</span>
			<script>
				function validateSuccess()
				{
					document.getElementById('insert_group').submit();
				}
			</script>
		</div>
	</form>
	</div>
</div>
<[include:footer]>
