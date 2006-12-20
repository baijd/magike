[module:admin_menu_module]
<div id="top_menu">
	<ul>
	[while@($admin_menu.parent,$menus)]
		<li><a href="{$static.index}{$menus.menu_path}" [if $menus.focus]class="focus"[/if]>{$menus.menu_name}</a></li>
	[/while]
	</ul>
</div>
<div id="menu_content"></div>