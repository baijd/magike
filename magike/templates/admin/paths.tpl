<[include:header]>
<[include:menu]>

<[module:path_input]>
<[module:paths_list]>
<div id="content">
	<div id="element">
	<[if:$path_input.open]>
		<div class="message">
			{$path_input.word}
		</div>
	<[/if]>
		<h2>路径列表 <span class="discribe">(这里列出了本网站所有虚拟路径,请谨慎操作)</span></h2>
		<form method="get" id="all_paths">
		<table width="100%" cellpadding="0" cellspacing="0" id="paths_list">
			<tr class="heading">
				<td width=5%>&nbsp;</td>
				<td width=25%>路径名称</td>
				<td width=20%>解析器</td>
				<td width=40%>路径</td>
				<td width=10%>操作</td>
			</tr>
			<[loop:$paths_list AS $path]>
			<tr>
				<td><input type="checkbox" class="checkbox_element" name="path_id[]" value="{$path.id}"/></td>
				<td><a href="{$static_var.index}/admin/paths/path/?path_id={$path.id}">{$path.path_describe}</a></td>
				<td>{$path.path_action}</td>
				<td class="describe">{$path.path_name}</td>
				<td>
					<a class="img" title="编辑" href="{$static_var.index}/admin/paths/path/?path_id={$path.id}"><img src="{$static_var.siteurl}/{!__TEMPLATE__}/{$static_var.admin_template}/images/edit.gif" alt="编辑"/></a> 
					<a class="img" title="删除" href="javascript:;" onclick="magikeConfirm(this);" msg="您确定删除路径 '{$path.path_describe}' 吗?" rel="{$static_var.index}/admin/paths/paths_list/?path_id={$path.id}&do=del"><img src="{$static_var.siteurl}/{!__TEMPLATE__}/{$static_var.admin_template}/images/delete.gif" alt="删除"/></a>
				</td>
			</tr>
			<[/loop]>
		</table>
		<input type="hidden" name="do" value="del"/>
		</form>
		<div class="table_nav">
			<span onclick="selectTableAll('paths_list','checkbox_element')">{lang.admin_db_grid.select_all}</span><b>,</b>
			<span onclick="selectTableNone('paths_list','checkbox_element')">{lang.admin_db_grid.select_none}</span><b>,</b>
			<span onclick="selectTableOther('paths_list','checkbox_element')">{lang.admin_db_grid.select_other}</span><b>,</b>
			<span onclick="if(confirm('您确定删除这些用户组吗?')) document.getElementById('all_paths').submit();">{lang.admin_db_grid.select_delete}</span>
	</div>
</div>

<script>
	registerTableCheckbox("paths_list","checkbox_element");
</script>
<[include:footer]>
