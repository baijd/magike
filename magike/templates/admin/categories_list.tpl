<[include:header]>
<[include:menu]>

<[module:category_input]>
<[module:categories_list]>
<div id="content">
	<div id="element">
	<[if:$category_input.open]>
		<div class="message">
			{$category_input.word}
		</div>
	<[/if]>
		<h2 style="border:none">{lang.admin_categories_list.categories_title} <span class="discribe">{lang.admin_categories_list.categories_describe}</span></h2>
		<form method="get" id="all_categories">
		<table width="100%" cellpadding="0" cellspacing="0" id="category_list">
			<tr class="heading">
				<td width=5%></td>
				<td width=20%>分类名称</td>
				<td width=35%>相关描述</td>
				<td width=20%>URL名称</td>
				<td width=20%>操作</td>
			</tr>
			<[loop:$categories_list AS $category]>
			<tr>
				<td><input type="checkbox" class="checkbox_element" name="c[]" value="{$category.id}"/></td>
				<td><a href="{$static_var.index}/admin/posts/category/?c={$category.id}" title="{$category.category_name}">{$category.category_name}</td>
				<td class="describe">{$category.category_describe}</td>
				<td>{$category.category_postname}</td>
				<td>
					<a class="img" title="编辑" href="{$static_var.index}/admin/posts/category/?c={$category.id}"><img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/edit.gif" alt="编辑"/></a> 
					<a class="img" title="删除" href="javascript:;" onclick="magikeConfirm(this);" msg="您确定删除 '{$category.category_name}' 吗?" rel="{$static_var.index}/admin/posts/categories_list/?c={$category.id}&do=del"><img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/delete.gif" alt="删除"/></a> 
					<a class="img" title="向上" href="{$static_var.index}/admin/posts/categories_list/?c={$category.id}&do=up"><img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/arrow_up.gif" alt="向上"/></a> 
					<a class="img" title="向下" href="{$static_var.index}/admin/posts/categories_list/?c={$category.id}&do=down"><img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/arrow_down.gif" alt="向下"/></a>
				</td>
			</tr>
			<[/loop]>
		</table>
		<input type="hidden" name="do" value="del"/>
		</form>
		<div class="table_nav">
			<span onclick="selectTableAll('category_list','checkbox_element')">{lang.admin_db_grid.select_all}</span><b>,</b>
			<span onclick="selectTableNone('category_list','checkbox_element')">{lang.admin_db_grid.select_none}</span><b>,</b>
			<span onclick="selectTableOther('category_list','checkbox_element')">{lang.admin_db_grid.select_other}</span><b>,</b>
			<span onclick="if(confirm('您确定删除这些分类吗')) document.getElementById('all_categories').submit();">{lang.admin_db_grid.select_delete}</span>
		</div>
	</div>
</div>

<script>
	registerTableCheckbox("category_list","checkbox_element");
</script>
<[include:footer]>