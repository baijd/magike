<[include:header]>
<[include:menu]>

<[module:comment_filter_input]>
<[module:comment_filters_list]>
<div id="content">
	<div id="element">
	<[if:$comment_filter_input.open]>
		<div class="message">
			{$comment_filter_input.word}
		</div>
	<[/if]>
		<h2 style="border:none">{lang.admin_comment_filters_list.filters_title} <span class="discribe">{lang.admin_comment_filters_list.filters_describe}</span></h2>
		<form method="get" id="comment_filters">
		<table width="100%" cellpadding="0" cellspacing="0" id="filter_list">
			<tr class="heading">
				<td width=5%>&nbsp;</td>
				<td width=30%>过滤器名称</td>
				<td width=40%>相关参数</td>
				<td width=15%>作用范围</td>
				<td width=10%>操作</td>
			</tr>
			<[loop:$comment_filters_list AS $filter]>
			<tr>
				<td><input type="checkbox" class="checkbox_element" name="cf_id[]" value="{$filter.id}"/></td>
				<td><a href="{$static_var.index}/admin/comments/filter/?cf_id={$filter.id}" title="{$filter.comment_filter_name}">{$filter.comment_filter_name}</td>
				<td class="describe">{$filter.comment_filter_value}</td>
				<td>{$filter.comment_filter_type}</td>
				<td>
					<a class="img" title="编辑" href="{$static_var.index}/admin/comments/filter/?cf_id={$filter.id}"><img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/edit.gif" alt="编辑"/></a> 
					<a class="img" title="删除" href="javascript:;" onclick="magikeConfirm(this);" msg="您确定删除 '{$filter.comment_filter_name}' 吗?" rel="{$static_var.index}/admin/comments/filters_list/?cf_id={$filter.id}&do=del"><img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/delete.gif" alt="删除"/></a> 
				</td>
			</tr>
			<[/loop]>
		</table>
		<input type="hidden" name="do" value="del"/>
		</form>
		<div class="table_nav">
			<span onclick="selectTableAll('filter_list','checkbox_element')">{lang.admin_db_grid.select_all}</span><b>,</b>
			<span onclick="selectTableNone('filter_list','checkbox_element')">{lang.admin_db_grid.select_none}</span><b>,</b>
			<span onclick="selectTableOther('filter_list','checkbox_element')">{lang.admin_db_grid.select_other}</span><b>,</b>
			<span onclick="if(confirm('您确定删除这些过滤器吗?')) document.getElementById('comment_filters').submit();">{lang.admin_db_grid.select_delete}</span>
		</div>
	</div>
</div>

<script>
	registerTableCheckbox("filter_list","checkbox_element");
</script>
<[include:footer]>