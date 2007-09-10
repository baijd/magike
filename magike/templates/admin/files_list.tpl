<[include:header]>
<[include:menu]>

<[module:file_input]>
<[module:files_list?limit=20]>
<[module:page_navigator.files_list?limit=20]>
<div id="content">
	<div id="element">
	<[if:$file_input.open]>
		<div class="message">
			{$file_input.word}
		</div>
	<[/if]>
		<h2 style="border:none">文件列表 <span class="discribe">(这里按时间先后列出了您上传的文件)</span></h2>
		<form method="get" id="all_files_list">
		<table width="100%" cellpadding="0" cellspacing="0" id="files_list">
			<tr class="heading">
				<td width=5%></td>
				<td width=25%>文件名称</td>
				<td width=25%>文件描述</td>
				<td width=20%>创建日期</td>
				<td width=15%>文件大小(B)</td>
				<td width=10%>操作</td>
			</tr>
			<[loop:$files_list AS $file]>
			<tr>
				<td><input type="checkbox" class="checkbox_element" name="file_id[]" value="{$file.id}"/></td>
				<td>{$file.file_name|length:10}</td>
				<td class="describe">{$file.file_describe}</td>
				<td class="describe">{$file.file_time|inttodate:Y-m-d H:i:s}</td>
				<td>{$file.file_size}</td>
				<td>
					<a class="img" title="删除" href="javascript:;" onclick="magikeConfirm(this);" msg="您确定删除文件 '{$file.file_name}' 吗?" rel="{$static_var.index}/admin/posts/files_list/?file_id={$file.id}&do=del"><img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/delete.gif" alt="删除"/></a>
					<[if:$file.is_image]>
					<a class="img" title="查看" href="javascript:;" onclick="imageView('{$file.view_thumbnail_permalink}','{$file.file_name}');" msg="您确定删除文件 '{$file.file_name}' 吗?" rel="{$static_var.index}/admin/posts/files_list/?file_id={$file.id}&do=del"><img src="{$static_var.siteurl}/templates/{$static_var.admin_template}/images/eye.gif" alt="查看"/></a>
					<[/if]>
				</td>
			</tr>
			<[/loop]>
		</table>
		<input type="hidden" name="do" value="del"/>
		</form>
		<div class="table_nav">
			<span onclick="selectTableAll('files_list','checkbox_element')">{lang.admin_db_grid.select_all}</span><b>,</b>
			<span onclick="selectTableNone('files_list','checkbox_element')">{lang.admin_db_grid.select_none}</span><b>,</b>
			<span onclick="selectTableOther('files_list','checkbox_element')">{lang.admin_db_grid.select_other}</span><b>,</b>
			<span onclick="if(confirm('您确定删除这些文件吗')) document.getElementById('all_files_list').submit();">{lang.admin_db_grid.select_delete}</span>
			<[if:$page_navigator.files_list.next]><a href="{$static_var.index}/admin/posts/files_list/?page={$page_navigator.files_list.next}">下一页</a><[/if]>
			<[if:$page_navigator.files_list.next and $page_navigator.files_list.prev]><u>,</u><[/if]>
			<[if:$page_navigator.files_list.prev]><a href="{$static_var.index}/admin/posts/files_list/?page={$page_navigator.files_list.prev}">上一页</a><[/if]>
		</div>
	</div>
</div>

<script>
	registerTableCheckbox("files_list","checkbox_element");
	function imageView(url,name)
	{
		div = $(document.createElement("div"));
		div.css("width","598px");
		div.css("height","400px");
		div.css("background","url("+url+") center no-repeat");
		
		magikeUI.createPopup({title: name,center: true,width: 600,height: 460,text:div,ok:"确定",cancel:"取消"});
	}
</script>
<[include:footer]>