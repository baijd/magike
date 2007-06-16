<[include:header]>
<[include:menu]>

<[module:link_category_input]>
<[module:link_categories]>
<div id="content">
	<div id="element">
	<[if:$link_category_input.open]>
		<div class="message">
			{$link_category_input.word}
		</div>
	<[/if]>
		<h2 style="border:none">链接分类列表 <span class="discribe">(这里列出了您所有的链接分类)</span></h2>
		<form method="get" id="all_link_categories">
		<table width="100%" cellpadding="0" cellspacing="0" id="link_category_list">
			<tr class="heading">
				<td width=5%></td>
				<td width=20%>分类名称</td>
				<td width=35%>相关描述</td>
				<td width=20%>链接排序</td>
				<td width=20%>操作</td>
			</tr>
			<[loop:$link_categories AS $link_category]>
			<tr>
				<td><input type="checkbox" class="checkbox_element" name="lc_id[]" value="{$link_category.id}"/></td>
				<td><a href="{$static_var.index}/admin/links/link_category/?lc_id={$link_category.id}" title="{$link_category.link_category_name}">{$link_category.link_category_name}</td>
				<td class="describe">{$link_category.link_category_describe}</td>
				<td><[if:$link_category.link_category_linksort == "asc"]>升序<[/if]><[if:$link_category.link_category_linksort == "desc"]>降序<[/if]><[if:$link_category.link_category_linksort == "rand"]>随机排序<[/if]></td>
				<td>
					<a class="img" title="编辑" href="{$static_var.index}/admin/links/link_category/?lc_id={$link_category.id}"><img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/edit.gif" alt="编辑"/></a> 
					<a class="img" title="删除" href="javascript:;" onclick="magikeConfirm(this);" msg="您确定删除链接分类 '{$link_category.link_category_name}' 吗?" rel="{$static_var.index}/admin/links/link_categories_list/?lc_id={$link_category.id}&do=del"><img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/delete.gif" alt="删除"/></a> 
					<a class="img" title="向上" href="{$static_var.index}/admin/links/link_categories_list/?lc_id={$link_category.id}&do=up"><img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/arrow_up.gif" alt="向上"/></a> 
					<a class="img" title="向下" href="{$static_var.index}/admin/links/link_categories_list/?lc_id={$link_category.id}&do=down"><img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/arrow_down.gif" alt="向下"/></a>
				</td>
			</tr>
			<[/loop]>
		</table>
		<input type="hidden" name="do" value="del"/>
		</form>
		<div class="table_nav">
			<span onclick="selectTableAll('link_category_list','checkbox_element')">{lang.admin_db_grid.select_all}</span><b>,</b>
			<span onclick="selectTableNone('link_category_list','checkbox_element')">{lang.admin_db_grid.select_none}</span><b>,</b>
			<span onclick="selectTableOther('link_category_list','checkbox_element')">{lang.admin_db_grid.select_other}</span><b>,</b>
			<span onclick="if(confirm('您确定删除这些分类吗')) document.getElementById('all_link_categories').submit();">{lang.admin_db_grid.select_delete}</span>
		</div>
	</div>
</div>

<script>
	registerTableCheckbox("link_category_list","checkbox_element");
</script>
<[include:footer]>