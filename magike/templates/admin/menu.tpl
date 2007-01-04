[module:admin_menu_module]
<div id="top_menu">
	<ul>
	[while@($admin_menu.parents,$parent)]
		<li><a href="{$static.index}{$parent.menu_path}" [if $parent.focus]class="focus"[/if]>{$parent.menu_name}</a></li>
	[/while]
	</ul>
</div>
<div id="menu_content">
	<ul>
	[while@($admin_menu.children,$child)]
		<li>
			<a href="{$static.index}{$child.menu_path}" [if $child.focus]class="focus"[/if]>
			<span class="button_content">{$child.menu_name}</span>
			<b class="xb4"></b><b class="xb3"></b><b class="xb2"></b><b class="xb1"></b>
			</a>
		</li>
	[/while]
	</ul>
</div>