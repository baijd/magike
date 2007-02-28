<section:include content="header"/>
<section:include content="menu"/>

<section:module content="categories_list"/>
<div id="content">
	<div id="element">
		<h2>{lang.admin_categories_list.categories_title} <span class="discribe">{lang.admin_categories_list.categories_describe}</span></h2>
		<table width="100%" cellpadding="0" cellspacing="0" id="category_list">
			<tr class="heading">
				<td width=5%>&nbsp;</td>
				<td width=20%>分类名称</td>
				<td width=35%>相关描述</td>
				<td width=20%>URL名称</td>
				<td width=20%>排序</td>
			</tr>
			<section:loop content="$categories_list AS $category">
			<tr>
				<td><input type="checkbox" class="checkbox_element" name="category[]" value="{$category.id}"/></td>
				<td><a href="{$static_var.index}/admin/posts/category?c={$category.id}" title="{$category.category_name}">{$category.category_name}</td>
				<td>{$category.category_describe}</td>
				<td>{$category.category_postname}</td>
				<td></td>
			</tr>
			</section:loop>
		</table>
		<div class="table_nav">
			<input type="button" class="button" value="{lang.admin_db_grid.select_all}" onclick="selectTableAll('category_list','checkbox_element')"/>
			<input type="button" class="button" value="{lang.admin_db_grid.select_none}" onclick="selectTableNone('category_list','checkbox_element')"/>
			<input type="button" class="button" value="{lang.admin_db_grid.select_other}" onclick="selectTableOther('category_list','checkbox_element')"/>
			<input type="button" class="button" value="{lang.admin_db_grid.select_delete}"/>
		</div>
	</div>
</div>

<script>
	registerTableCheckbox("category_list","checkbox_element");
</script>
<section:include content="footer"/>