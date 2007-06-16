<[include:header]>
<[include:menu]>

<[module:post_input]>
<[module:posts_list?type=2&sub=20&striptags=1&limit=20]>
<[module:posts_list_page_nav?type=2&limit=20]>
<div id="content">
	<div id="element">
	<[if:$post_input.open]>
		<div class="message">
			{$post_input.word}
			<[if:$post_input.trackback]>
				<[loop:$post_input.trackback AS $trackback]>
					<br/>"{$trackback}" 已经成功发送
				<[/loop]>
			<[/if]>
		</div>
	<[/if]>
		<h2 style="border:none">{lang.admin_posts_list.list_title} <span class="discribe">{lang.admin_posts_list.list_describe}</span></h2>
		<div id="search"><form method="get" action="{$static_var.index}/admin/posts/all/search/"><strong>关键字:</strong> <input type="text" size=20 name="keywords" /> <input type="checkbox" name="title" value="1" />按标题
		 <input type="checkbox" name="tags" value="1" />按标签 <input type="checkbox" name="content" value="1" />按内容 <input type="checkbox" name="category" value="1" />按分类 <input type="submit" value="搜索" /></form></div>
		<form method="get" id="all_posts">
		<table width="100%" cellpadding="0" cellspacing="0" id="post_list">
			<tr class="heading">
				<td width=5%>&nbsp;</td>
				<td width=15%>标题</td>
				<td width=5%></td>
				<td width=30%>摘要</td>
				<td width=20%>分类</td>
				<td width=15%>发布日期</td>
				<td width=10%>操作</td>
			</tr>
			<[loop:$posts_list AS $post]>
			<tr>
				<td><input type="checkbox" class="checkbox_element" name="post_id[]" value="{$post.post_id}"/></td>
				<td><a href="{$static_var.index}/admin/posts/write/?post_id={$post.post_id}" title="{$post.post_title}">{$post.post_title}</a>
				</td>
				<td>
				<[if:$post.post_is_page]><img class="describe" style="float:left" src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/page.gif" title="这是一个页面" alt="这是一个页面"/><[/if]>
				<[if:$post.post_is_draft]><img class="describe" src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/draft.gif" title="这是一篇草稿" alt="这是一篇草稿"/><[/if]>
				</td>
				<td class="describe">{$post.post_content}</td>
				<td>{$post.category_name}</td>
				<td>{$post.post_time}</td>
				<td>
					<a class="img" title="编辑" href="{$static_var.index}/admin/posts/write/?post_id={$post.post_id}"><img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/edit.gif" alt="编辑"/></a> 
					<a class="img" title="删除" href="javascript:;" onclick="magikeConfirm(this);" msg="您确定删除 '{$post.post_title}' 吗?" rel="{$static_var.index}/admin/posts/all/?post_id={$post.post_id}&do=del"><img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/delete.gif" alt="删除"/></a> 
				</td>
			</tr>
			<[/loop]>
		</table>
		<input type="hidden" name="do" value="del"/>
		</form>
		<div class="table_nav">
			<span onclick="selectTableAll('post_list','checkbox_element')">{lang.admin_db_grid.select_all}</span><b>,</b>
			<span onclick="selectTableNone('post_list','checkbox_element')">{lang.admin_db_grid.select_none}</span><b>,</b>
			<span onclick="selectTableOther('post_list','checkbox_element')">{lang.admin_db_grid.select_other}</span><b>,</b>
			<span onclick="if(confirm('您确定删除这些文章吗?')) document.getElementById('all_posts').submit();">{lang.admin_db_grid.select_delete}</span><b>,</b>
			<span id="search_btn">搜索</span>
			<[if:$posts_list_page_nav.next]><a href="{$static_var.index}/admin/posts/all/?page={$posts_list_page_nav.next}">下一页</a><[/if]>
			<[if:$posts_list_page_nav.next and $posts_list_page_nav.prev]><u>,</u><[/if]>
			<[if:$posts_list_page_nav.prev]><a href="{$static_var.index}/admin/posts/all/?page={$posts_list_page_nav.prev}">上一页</a><[/if]>
	</div>
</div>
<script>
	registerTableCheckbox("post_list","checkbox_element");
	$("#search_btn").toggle(function(){$('#search').slideDown();},function(){$('#search').slideUp();});
</script>
<[include:footer]>