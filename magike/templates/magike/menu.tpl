<[module:pages_list]>
<div id="menu_content">
	<ul>
		<li><a href="{$static_var.siteurl}" class="focus">
		Home
		</a></li>
		<[loop:$pages_list AS $page]>
		<li><a href="{$static_var.index}/{$page.post_name}/">
		{$page.post_title}
		</a></li>
		<[/loop]>
		<[if:$access.login]>
		<li><a href="{$static_var.index}/admin/">后台管理</a></li>
		<li><a href="{$static_var.index}/admin/logout/">登出({$access.user_name})</a></li>
		<[/if]>
		<[if:!$access.login]>
		<li><a href="{$static_var.index}/admin/login">登录</a></li>
		<[/if]>
	</ul>
</div>
