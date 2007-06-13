<[module:pages_list]>
<div id="menu_content">
	<ul>
		<[if:$access.login]>
		<li><a href="{$static_var.index}/admin/logout/">Logout({$access.user_name})</a></li>
		<li><a href="{$static_var.index}/admin/">Dashborad</a></li>
		<[/if]>
		<[if:!$access.login]>
		<li><a href="{$static_var.index}/admin/login">Login</a></li>
		<[/if]>
		<[loop:$pages_list AS $page]>
		<li><a href="{$static_var.index}/{$page.post_name}/">
		{$page.post_title}
		</a></li>
		<[/loop]>
		<li><a href="{$static_var.siteurl}" class="focus">
		Homepage
		</a></li>
	</ul>
</div>
<div id="banner">
</div>
