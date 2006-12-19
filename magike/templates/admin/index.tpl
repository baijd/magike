[include:header]
[include:menu]

[module:admin_index_module]
<div id="content">
	<div id="element">
		<h2>{lang.admin_index.global_runtime}</h2>
		<table width=100% width="100%" border="0" cellspacing="1" cellpadding="0">
			<tr>
				<td width=30%>{lang.admin_index.server_version}</td>
				<td width=70%>{$admin_index.server_version}</td>
			</tr>
			<tr>
				<td>{lang.admin_index.database_version}</td>
				<td>{$admin_index.database_version}</td>
			</tr>
			<tr>
				<td>{lang.admin_index.magike_version}</td>
				<td>{$admin_index.magike_version}</td>
			</tr>
			<tr>
				<td>{lang.admin_index.posts_num}</td>
				<td>{$admin_index.posts_num} {lang.admin_index.posts}</td>
			</tr>
		</table>
	</div>
</div>

[include:footer]
