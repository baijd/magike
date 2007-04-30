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
				<td width=30%>摘要</td>
				<td width=20%>分类</td>
				<td width=15%>发布日期</td>
				<td width=10%>操作</td>
			</tr>
			<[loop:$posts_list AS $post]>
			<tr>
				<td><input type="checkbox" class="checkbox_element" name="post[]" value="{$post.post_id}"/></td>
				<td><a href="{$static_var.index}/admin/posts/write?post_id={$post.post_id}" title="{$post.post_title}">{$post.post_title}</a></td>
				<td>{$post.post_content}</td>
				<td>{$post.category_name}</td>
				<td>{$post.post_time}</td>
				<td>
					<a class="img" title="编辑" href="{$static_var.index}/admin/posts/write?post_id={$post.post_id}"><img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/edit.gif" alt="编辑"/></a> 
					<a class="img" title="删除" href="javascript:;" onclick="magikeConfirm(this);" msg="您确定删除 '{$post.post_title}' 吗?" rel="{$static_var.index}/admin/posts/all?post_id={$post.post_id}&act=del"><img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/delete.gif" alt="删除"/></a> 
				</td>
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