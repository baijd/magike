<[include:header]>
<[include:menu]>

<[module:posts_list?type=2&sub=20]>
<div id="content">
	<div id="element">
		<h2>{lang.admin_posts_list.list_title} <span class="discribe">{lang.admin_posts_list.list_describe}</span></h2>
		<table width="100%" cellpadding="0" cellspacing="0" id="post_list">
			<tr class="heading">
				<td width=5%>&nbsp;</td>
				<td width=20%>标题</td>
				<td width=40%>摘要</td>
				<td width=20%>分类</td>
				<td width=15%>发布日期</td>
			</tr>
			<[loop:$posts_list AS $post]>
			<tr>
				<td width=5%><input type="checkbox" class="checkbox_element" name="post[]" value="{$post.post_id}"/></td>
				<td width=20%><a href="{$static_var.index}/admin/posts/write?post_id={$post.post_id}" title="{$post.post_title}">{$post.post_title}</a></td>
				<td width=40%>{$post.post_content}</td>
				<td width=20%>{$post.category_name}</td>
				<td width=15%>{$post.post_time}</td>
			</tr>
			<[/loop]>
		</table>
		<div class="table_nav">
			<span onclick="selectTableAll('post_list','checkbox_element')">{lang.admin_db_grid.select_all}</span>,
			<span onclick="selectTableNone('post_list','checkbox_element')">{lang.admin_db_grid.select_none}</span>,
			<span onclick="selectTableOther('post_list','checkbox_element')">{lang.admin_db_grid.select_other}</span>,
			<span>{lang.admin_db_grid.select_delete}</span>
		</div>
	</div>
</div>
<script>
	registerTableCheckbox("post_list","checkbox_element");
</script>
<[include:footer]>