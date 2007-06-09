<[include:header]>
<[include:menu]>

<[module:insert_path]>
<[module:groups_list]>
<[module:actions_list]>
<div id="content">
	<div id="element">
		<div class="proc">
			正在处理您的请求
		</div>
	<form method="post" id="insert_path" action="{$static_var.index}/admin/paths/paths_list/?<[if:$insert_path.do == "update"]>path_id={$insert_path.id}&do=update<[/if]><[if:$insert_path.do == "insert"]>do=insert<[/if]>">
		<h2>增加或编辑路径 <span class="discribe">(您可以在这里操作虚拟路径)</span></h2>
		<div class="input">
			<h2>路径描述名称</h2>
			<p>
				<input type="text" class="text validate-me" name="path_describe" value="{$insert_path.path_describe}" size=60 /><span class="validate-word" id="path_describe-word"></span><br />
				<span class="discribe">(描述这个路径,比如:网站主页)</span>
			</p>
		</div>
		<div class="input">
			<h2>路径地址</h2>
			<p>
				<input type="text" class="text validate-me" name="path_name" value="{$insert_path.path_name}" size=60 /><span class="validate-word" id="path_name-word"></span><br />
				<span class="discribe">(指向这个路径的地址,详细设置请参考帮助信息)</span>
			</p>
		</div>
		<div class="input">
			<h2>路径地址</h2>
			<p>
				<input type="text" class="text validate-me" name="path_file" value="{$insert_path.path_file}" size=60 /><span class="validate-word" id="path_file-word"></span><br />
				<span class="discribe">(虚拟路径的解析地址,详细设置请参考帮助信息)</span>
			</p>
		</div>
		<div class="input">
			<h2>页面缓存过期时间</h2>
			<p>
				<input type="text" class="text validate-me" name="path_cache" value="{$insert_path.path_cache}" size=60 /><span class="validate-word" id="path_cache-word"></span><br />
				<span class="discribe">(单位为秒,如果为0则表示不启用页面过期缓存机制)</span>
			</p>
		</div>
		<div class="input">
			<h2>解析器</h2>
			<p>
				<select name="path_action" class="validate-me">
				<[loop:$actions_list AS $action]>
					<option value="{$action.value}" <[if:$action.value == $insert_path.path_action]>selected=true<[/if]>>{$action.word}</option>
				<[/loop]>
				</select><span class="validate-word" id="path_action-word"></span><br />
				<span class="discribe">(为这个路径选择一个解析器)</span>
			</p>
		</div>
		<div class="input">
			<h2>群组权限</h2>
			<p>
				<[loop:$groups_list AS $group]>
					<input type="checkbox" name="path_group[]" class="checkbox" value="{$group.id}" <[if:in_array($group.id,$insert_path.path_group)]>checked=true<[/if]> /> {$group.group_name} <span class="discribe">{$group.group_describe}</span><br />
				<[/loop]>
				<span class="discribe">(能够访问该路径的用户组)</span>
			</p>
		</div>
		<div class="submit">
			<span class="button" onclick="magikeValidator('{$static_var.index}/helper/validator/','add_path');">提交信息</span>
			<script>
				function validateSuccess()
				{
					document.getElementById('insert_path').submit();
				}
			</script>
		</div>
	</form>
	</div>
</div>
<[include:footer]>
