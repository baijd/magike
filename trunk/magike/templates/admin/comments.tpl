<[include:header]>
<[include:menu]>

<[module:comment_input]>
<[module:comments?limit=20&striptags=1&substr=30]>
<[module:page_navigator.comments?limit=20]>
<div id="content">
	<div id="element">
	<[if:$comment_input.open]>
		<div class="message">
			{$comment_input.word}
		</div>
	<[/if]>
		<h2 style="border:none">{lang.admin_comment.comment_list} <span class="discribe">{lang.admin_comment.comment_list_describe}</span></h2>
		<form method="get" id="all_comments">
		<table width="100%" cellpadding="0" cellspacing="0" id="comment_list">
			<tr class="heading">
				<td width=5%>&nbsp;</td>
				<td width=10%>发表者</td>
				<td width=8%></td>
				<td width=15%>IP地址</td>
				<td width=35%>摘要</td>
				<td width=17%>发表日期</td>
				<td width=10%>操作</td>
			</tr>
			<[loop:$comments AS $comment]>
			<tr id="drag-{$comment.comment_id}">
				<td><input type="checkbox" class="checkbox_element" name="comment_id[]" value="{$comment.comment_id}"/></td>
				<td>{$comment.comment_user}
				</td>
				<td>
				<[if:$comment.comment_type == "pingback"]><img class="describe" src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/pingback.gif" title="这是一条引用通告(pingback)" alt="这是一条引用通告(pingback)"/><[/if]>
				<[if:$comment.comment_type == "trackback"]><img class="describe" src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/trackback.gif" title="这是一条引用通告(trackback)" alt="这是一条引用通告(trackback)"/><[/if]>
				<[if:$comment.comment_type == "comment"]><img class="describe" src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/comment.gif" title="这是一条评论" alt="这是一条评论"/><[/if]>
				<[if:$comment.comment_homepage]><a class="img" title="{$comment.comment_homepage}" href="{$comment.comment_homepage}"><img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/homepage.gif" style="float:none" title="{$comment.comment_homepage}" alt="{$comment.comment_homepage}"/></a><[/if]>
				<[if:$comment.comment_email]><a class="img" title="{$comment.comment_email}" href="mailto:{$comment.comment_email}"><img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/email.gif" style="float:none" title="{$comment.comment_email}" alt="{$comment.comment_email}"/></a><[/if]>
				</td>
				<td>{$comment.comment_ip}</td>
				<td class="discribe">{$comment.comment_text}</td>
				<td>{$comment.comment_date}</td>
				<td>
					<[if:$comment.comment_publish != "approved"]><a class="img" title="标记为展现" href="javascript:;" onclick="magikeConfirm(this);" msg="您确定将 '{$comment.comment_user}' 的这条评论展现吗?" rel="{$static_var.index}/admin/comments/all/?comment_id={$comment.comment_id}&do=approved"><img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/comment_approved.gif" alt="标记为展现"/></a><[/if]>
					<[if:$comment.comment_publish == "approved"]><i class="img" title="展现"><img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/comment_approved.gif" alt="展现"/></i><[/if]>
					<[if:$comment.comment_publish != "waitting"]><a class="img" title="标记为待审核" href="javascript:;" onclick="magikeConfirm(this);" msg="您确定将 '{$comment.comment_user}' 的这条评论标记为待审核吗?" rel="{$static_var.index}/admin/comments/all/?comment_id={$comment.comment_id}&do=waitting"><img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/comment_waitting.gif" alt="标记为待审核"/></a><[/if]>
					<[if:$comment.comment_publish == "waitting"]><i class="img" title="待审核"><img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/comment_waitting.gif" alt="待审核"/></i><[/if]>
					<[if:$comment.comment_publish != "spam"]><a class="img" title="标记为垃圾回响" href="javascript:;" onclick="magikeConfirm(this);" msg="您确定将 '{$comment.comment_user}' 的这条评论标记为垃圾吗?" rel="{$static_var.index}/admin/comments/all/?comment_id={$comment.comment_id}&do=spam"><img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/comment_spam.gif" alt="标记为垃圾回响"/></a><[/if]>
					<[if:$comment.comment_publish == "spam"]><i class="img" title="垃圾回响"><img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/comment_spam.gif" alt="垃圾回响"/></i><[/if]>
					<a class="img" title="删除" href="javascript:;" onclick="magikeConfirm(this);" msg="您确定删除 '{$comment.comment_user}' 的这条评论吗?" rel="{$static_var.index}/admin/comments/all/?comment_id={$comment.comment_id}&do=del"><img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/delete.gif" alt="删除"/></a>
				</td>
			</tr>
			<[/loop]>
		</table>
		<input type="hidden" name="do" id="do" value="del"/>
		</form>
		<div class="table_nav">
			<span onclick="selectTableAll('comment_list','checkbox_element')">{lang.admin_db_grid.select_all}</span><b>,</b>
			<span onclick="selectTableNone('comment_list','checkbox_element')">{lang.admin_db_grid.select_none}</span><b>,</b>
			<span onclick="selectTableOther('comment_list','checkbox_element')">{lang.admin_db_grid.select_other}</span><b>,</b>
			<span onclick="if(confirm('您确定删除这些评论吗?')) {$('#do').val('del'); document.getElementById('all_comments').submit();}">{lang.admin_db_grid.select_delete}</span><b>,</b>
			<span onclick="if(confirm('您确定将这些评论展现吗?')) {$('#do').val('approved'); document.getElementById('all_comments').submit();}">展现</span><b>,</b>
			<span onclick="if(confirm('您确定将这些评论标记为待审核吗?')) {$('#do').val('waitting'); document.getElementById('all_comments').submit();}">待审核</span><b>,</b>
			<span onclick="if(confirm('您确定将这些评论标记为垃圾吗?')) {$('#do').val('spam'); document.getElementById('all_comments').submit();}">垃圾评论</span>
			<[if:$page_navigator.comments.next]><a href="{$page_navigator.comments.next_permalink}">下一页</a><[/if]>
			<[if:$page_navigator.comments.next and $page_navigator.comments.prev]><u>,</u><[/if]>
			<[if:$page_navigator.comments.prev]><a href="{$page_navigator.comments.prev_permalink}">上一页</a><[/if]>
		</div>
	</div>
</div>

<script>
	registerTableCheckbox("comment_list","checkbox_element");
	tabBtn = $("#tab_first_button");
</script>
<[include:footer]>