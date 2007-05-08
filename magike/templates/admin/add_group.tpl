<[include:header]>
<[include:menu]>

<[module:insert_group]>
<[module:paths_list]>
<div id="content">
	<div id="element">
		<div class="proc">
			正在处理您的请求
		</div>
	<form method="post" id="insert_group" action="{$static_var.index}/admin/users/groups_list/?<[if:$insert_group.do == "update"]>group_id={$insert_group.id}&do=update<[/if]><[if:$insert_group.do == "insert"]>do=insert<[/if]>">
		<h2>增加或编辑用户组 <span class="discribe">(您可以在这里操作用户组的权限)</span></h2>
		<div class="input">
			<h2>用户组</h2>
			<p>
				<input type="text" class="text validate-me" name="group_name" value="{$insert_group.group_name}" size=60 /><span class="validate-word" id="group_name-word"></span><br />
				<span class="discribe">(为您的用户组分配一个名称,比如:管理员)</span>
			</p>
		</div>
		<div class="input">
			<h2>用户组描述</h2>
			<p>
				<input type="text" class="text validate-me" name="group_describe" value="{$insert_group.group_describe}" size=60 /><br />
				<span class="discribe">(描述这个用户组,如果没有必要,可以留空)</span>
			</p>
		</div>
		<div class="input">
			<h2>用户组权限</h2>
				<ul style="margin:10px 5px;padding:0;float:left;width:600px;font-size:9pt;color:#999">
				<[loop:$paths_list AS $path]>
					<li style="margin:0;list-style:none;float:left;width:200px;height:22px;"><input type="checkbox" class="checkbox validate-me" name="group_path[]" value="{$path.id}" <[if:in_array($path.id,$insert_group.group_path)]>checked=true<[/if]> /> {$path.path_describe}</li>
				<[/loop]>
					<li style="margin:0;list-style:none;float:left;width:600px;height:26px;"><input type="button" value="全选" onclick="$('input.checkbox').attr('checked','true');" /> <input type="button" onclick="$('input.checkbox').removeAttr('checked');" value="全不选" /></li>
					<li style="margin:0;list-style:none;float:left;width:600px;height:22px;">(描述这个用户组,如果没有必要,可以留空)</li>
				</ul>
				
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
