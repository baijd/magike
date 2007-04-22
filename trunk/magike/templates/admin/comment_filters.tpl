<[include:header]>
<[include:menu]>

<[module:comment_filters_list]>
<div id="content">
	<div id="element">
		<h2>{lang.admin_comment_filters_list.filters_title} <span class="discribe">{lang.admin_comment_filters_list.filters_describe}</span></h2>
		<table width="100%" cellpadding="0" cellspacing="0" id="filter_list">
			<tr class="heading">
				<td width=5%>&nbsp;</td>
				<td width=50%>过滤器名称</td>
				<td width=30%>相关参数</td>
				<td width=15%>作用范围</td>
			</tr>
			<[loop:$comment_filters_list AS $filter]>
			<tr>
				<td><input type="checkbox" class="checkbox_element" name="filter[]" value="{$filter.id}"/></td>
				<td><a href="{$static_var.index}/admin/comments/filter?id={$filter.id}" title="{$filter.comment_filter_name}">{$filter.comment_filter_name}</td>
				<td>{$filter.comment_filter_value}</td>
				<td>{$filter.comment_filter_type}</td>
			</tr>
			<[/loop]>
		</table>
		<div class="table_nav">
			<span onclick="selectTableAll('filter_list','checkbox_element')">{lang.admin_db_grid.select_all}</span>,
			<span onclick="selectTableNone('filter_list','checkbox_element')">{lang.admin_db_grid.select_none}</span>,
			<span onclick="selectTableOther('filter_list','checkbox_element')">{lang.admin_db_grid.select_other}</span>,
			<span>{lang.admin_db_grid.select_delete}</span>
		</div>
	</div>
</div>

<script>
	registerTableCheckbox("filter_list","checkbox_element");
</script>
<[include:footer]>