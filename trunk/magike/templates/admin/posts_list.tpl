<section:include content="header"/>
<section:include content="menu"/>

<section:module content="categories_list"/>
<section:module content="posts_list"/>
<div id="content">
	<div id="element">
		<p>
			<div class="tab_nav">
				<ul id="tab">
					<li rel="post_list_table" id="first"><span>文章列表</span></li>
					<li rel="post_search"><span>高级搜索</span></li>
					<li><span>更多操作</span></li>
				</ul>
			</div>
			<div class="tab_content" id="post_tab">
				<div id="post_list_table">
					<h2>{lang.admin_posts_list.list_title} <span class="discribe">{lang.admin_posts_list.list_describe}</span></h2>
					<table width="100%" cellpadding="0" cellspacing="0" id="post_list">
						<tr class="heading">
							<td width=5%>&nbsp;</td>
							<td width=20%>标题</td>
							<td width=40%>摘要</td>
							<td width=20%>分类</td>
							<td width=15%>发布日期</td>
						</tr>
						<section:loop content="$posts_list AS $post">
						<tr>
							<td width=5%><input type="checkbox" class="checkbox_element" name="post[]" value="{$post.post_id}"/></td>
							<td width=20%><a href="{$static_var.index}/admin/posts/write?post_id={$post.post_id}" title="{$post.post_title}">{$post.post_title}</a></td>
							<td width=40%>摘要</td>
							<td width=20%>{$post.category_name}</td>
							<td width=15%>{$post.post_time}</td>
						</tr>
						</section:loop>
					</table>
					<div class="table_nav">
						<span onclick="selectTableAll('post_list','checkbox_element')">{lang.admin_db_grid.select_all}</span>,
						<span onclick="selectTableNone('post_list','checkbox_element')">{lang.admin_db_grid.select_none}</span>,
						<span onclick="selectTableOther('post_list','checkbox_element')">{lang.admin_db_grid.select_other}</span>,
						<span>{lang.admin_db_grid.select_delete}</span>
					</div>
				</div>
				<div id="post_search">
					<input type="text" class="text" name="s" />
				</div>
			</div>
		</p>
	</div>
</div>
<script>
	registerTableCheckbox("post_list","checkbox_element");
	registerTab("#tab","#post_tab");
</script>
<section:include content="footer"/>