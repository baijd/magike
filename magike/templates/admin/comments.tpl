<section:include content="header"/>
<section:include content="menu"/>

<section:module content="comments_list?limit=20&list=1&substr=20"/>
<div id="content">
	<div id="element">
		<h2>{lang.admin_comment.comment_list} <span class="discribe">{lang.admin_comment.comment_list_describe}</span></h2>
		<form method="get">
		<table width="100%" cellpadding="0" cellspacing="0" id="comment_list">
			<tr class="heading">
				<td width=5%>&nbsp;</td>
				<td width=15%>发表者</td>
				<td widht=15%>IP地址</td>
				<td width=30%>摘要</td>
				<td width=10%>类别</td>
				<td width=10%>发表日期</td>
				<td width=15%>状态</td>
			</tr>
			<section:loop content="$comments_list AS $comment">
			<tr>
				<td><input type="checkbox" class="checkbox_element" name="comment[]" value="{$comment.id}"/></td>
				<td>{$comment.comment_user}</td>
				<td>{$comment.comment_ip}</td>
				<td>{$comment.comment_text}</td>
				<td></td>
				<td>{$comment.comment_date}</td>
				<td></td>
			</tr>
			</section:loop>
		</table>
		<div class="table_nav">
			<input type="button" class="button" value="{lang.admin_db_grid.select_all}" onclick="selectTableAll('comment_list','checkbox_element')"/>
			<input type="button" class="button" value="{lang.admin_db_grid.select_none}" onclick="selectTableNone('comment_list','checkbox_element')"/>
			<input type="button" class="button" value="{lang.admin_db_grid.select_other}" onclick="selectTableOther('comment_list','checkbox_element')"/>
			<input type="button" class="button" value="{lang.admin_db_grid.select_delete}"/>
		</div>
		</form>
	</div>
</div>

<script>
	registerTableCheckbox("comment_list","checkbox_element");
	tabBtn = $("#tab_first_button");
</script>
<section:include content="footer"/>