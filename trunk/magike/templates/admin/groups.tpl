<[include:header]>
<[include:menu]>

<[module:group_input]>
<[module:groups_list]>
<div id="content">
	<div id="element">
	<[if:$group_input.open]>
		<div class="message">
			{$group_input.word}
		</div>
	<[/if]>
		<h2 style="border:none">用户组列表 <span class="discribe">(这里列出了本网站所有的用户组)</span></h2>
		<form method="get" id="all_groups">
		<table width="100%" cellpadding="0" cellspacing="0" id="groups_list">
			<tr class="heading">
				<td width=5%>&nbsp;</td>
				<td width=25%>用户组名称</td>
				<td width=60%>用户组描述</td>
				<td width=10%>操作</td>
			</tr>
			<[loop:$groups_list AS $group]>
			<tr>
				<td><input type="checkbox" class="checkbox_element" name="group_id[]" value="{$group.id}"/></td>
				<td><a href="{$static_var.index}/admin/users/group/?group_id={$group.id}">{$group.group_name}</a></td>
				<td class="describe">{$group.group_describe}</td>
				<td>
					<a class="img" title="编辑" href="{$static_var.index}/admin/users/group/?group_id={$group.id}"><img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/edit.gif" alt="编辑"/></a> 
					<a class="img" title="删除" href="javascript:;" onclick="magikeConfirm(this);" msg="您确定删除用户组 '{$group.group_name}' 吗?" rel="{$static_var.index}/admin/users/groups_list/?group_id={$group.id}&do=del"><img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/delete.gif" alt="删除"/></a>
				</td>
			</tr>
			<[/loop]>
		</table>
		<input type="hidden" name="do" value="del"/>
		</form>
		<div class="table_nav">
			<span onclick="selectTableAll('groups_list','checkbox_element')">{lang.admin_db_grid.select_all}</span><b>,</b>
			<span onclick="selectTableNone('groups_list','checkbox_element')">{lang.admin_db_grid.select_none}</span><b>,</b>
			<span onclick="selectTableOther('groups_list','checkbox_element')">{lang.admin_db_grid.select_other}</span><b>,</b>
			<span onclick="if(confirm('您确定删除这些用户组吗?')) document.getElementById('all_groups').submit();">{lang.admin_db_grid.select_delete}</span>
	</div>
</div>

<script>
	registerTableCheckbox("groups_list","checkbox_element");
</script>
<[include:footer]>