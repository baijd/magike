<section:include content="header"/>
<section:include content="menu"/>

<section:module content="comment_filters_list"/>
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
			<section:loop content="$comment_filters_list AS $filter">
			<tr>
				<td><input type="checkbox" class="checkbox_element" name="filter[]" value="{$filter.id}"/></td>
				<td><a href="{$static_var.index}/admin/comments/filter?id={$filter.id}" title="{$filter.comment_filter_name}">{$filter.comment_filter_name}</td>
				<td>{$filter.comment_filter_value}</td>
				<td>{$filter.comment_filter_type}</td>
			</tr>
			</section:loop>
		</table>
		<div class="table_nav">
			<input type="button" class="button" value="{lang.admin_db_grid.select_all}" onclick="selectTableAll('filter_list','checkbox_element')"/>
			<input type="button" class="button" value="{lang.admin_db_grid.select_none}" onclick="selectTableNone('filter_list','checkbox_element')"/>
			<input type="button" class="button" value="{lang.admin_db_grid.select_other}" onclick="selectTableOther('filter_list','checkbox_element')"/>
			<input type="button" class="button" value="{lang.admin_db_grid.select_delete}"/>
		</div>
	</div>
</div>

<script>
	registerTableCheckbox("filter_list","checkbox_element");
</script>
<section:include content="footer"/>