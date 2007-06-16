<[include:header]>
<[include:menu]>

<[module:link_input]>
<[module:links_list]>
<[module:links_page_nav]>
<div id="content">
	<div id="element">
	<[if:$link_input.open]>
		<div class="message">
			{$link_input.word}
		</div>
	<[/if]>
		<h2 style="border:none">{lang.admin_links.links_title} <span class="discribe">{lang.admin_links.links_describe}</span></h2>
		<form method="get" id="all_links">
		<table width="100%" cellpadding="0" cellspacing="0" id="link_list">
			<tr class="heading">
				<td width=5%>&nbsp;</td>
				<td width=15%>链接名称</td>
				<td width=30%>链接地址</td>
				<td width=25%>描述</td>
				<td width=15%>所属分类</td>
				<td width=10%>操作</td>
			</tr>
			<[loop:$links_list AS $link]>
			<tr>
				<td><input type="checkbox" class="checkbox_element" name="link_id[]" value="{$link.link_id}"/></td>
				<td><a href="{$static_var.index}/admin/links/link/?link_id={$link.link_id}" title="{$link.link_name}">{$link.link_name}</a></td>
				<td><a href="{$link.link_url}" title="{$link.link_name}">{$link.link_url}</a></td>
				<td class="describe">{$link.link_describe}</td>
				<td>{$link.link_category_name}</td>
				<td>
					<a class="img" title="编辑" href="{$static_var.index}/admin/links/link/?link_id={$link.link_id}"><img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/edit.gif" alt="编辑"/></a> 
					<a class="img" title="删除" href="javascript:;" onclick="magikeConfirm(this);" msg="您确定删除 '{$link.link_name}' 吗?" rel="{$static_var.index}/admin/links/link_list/?link_id={$link.link_id}&do=del"><img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/delete.gif" alt="删除"/></a>
				</td>
			</tr>
			<[/loop]>
		</table>
		<input type="hidden" name="do" value="del"/>
		</form>
		<div class="table_nav">
			<span onclick="selectTableAll('link_list','checkbox_element')">{lang.admin_db_grid.select_all}</span><b>,</b>
			<span onclick="selectTableNone('link_list','checkbox_element')">{lang.admin_db_grid.select_none}</span><b>,</b>
			<span onclick="selectTableOther('link_list','checkbox_element')">{lang.admin_db_grid.select_other}</span><b>,</b>
			<span onclick="if(confirm('您确定删除这些链接吗?')) document.getElementById('all_links').submit();">{lang.admin_db_grid.select_delete}</span>
			<[if:$links_page_nav.next]><a href="{$static_var.index}/admin/comments/all/?comment_page={$links_page_nav.next}">下一页</a><[/if]>
			<[if:$links_page_nav.next and $links_page_nav.prev]><u>,</u><[/if]>
			<[if:$links_page_nav.prev]><a href="{$static_var.index}/admin/comments/all/?comment_page={$links_page_nav.prev}">上一页</a><[/if]>
		</div>
	</div>
</div>

<script>
	registerTableCheckbox("link_list","checkbox_element");
</script>
<[include:footer]>