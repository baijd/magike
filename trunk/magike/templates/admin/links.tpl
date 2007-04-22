<[include:header]>
<[include:menu]>

<[module:links_list]>
<div id="content">
	<div id="element">
		<h2>{lang.admin_links.links_title} <span class="discribe">{lang.admin_links.links_describe}</span></h2>
		<table width="100%" cellpadding="0" cellspacing="0" id="link_list">
			<tr class="heading">
				<td width=5%>&nbsp;</td>
				<td width=20%>链接名称</td>
				<td width=40%>链接地址</td>
				<td width=20%>描述</td>
				<td width=15%>所属分类</td>
			</tr>
			<[loop:$links_list AS $link]>
			<tr>
				<td width=5%><input type="checkbox" class="checkbox_element" name="link[]" value="{$link.link_id}"/</td>
				<td width=20%><a href="#">{$link.link_name}</a></td>
				<td width=40%><a href="{$link.link_url}" title="{$link.link_name}">{$link.link_url}</a></td>
				<td width=20%>{$link.link_describe}</td>
				<td width=15%>{$link.link_category_name}</td>
			</tr>
			<[/loop]>
		</table>
		<div class="table_nav">
			<span onclick="selectTableAll('link_list','checkbox_element')">{lang.admin_db_grid.select_all}</span>,
			<span onclick="selectTableNone('link_list','checkbox_element')">{lang.admin_db_grid.select_none}</span>,
			<span onclick="selectTableOther('link_list','checkbox_element')">{lang.admin_db_grid.select_other}</span>,
			<span>{lang.admin_db_grid.select_delete}</span>
		</div>
	</div>
</div>

<script>
	registerTableCheckbox("link_list","checkbox_element");
</script>
<[include:footer]>