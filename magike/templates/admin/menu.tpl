<[module:admin_menu_list]>
<div id="top_menu">
	<ul>
	<[loop:$admin_menu_list.parents as $parent]>
		<li><a href="{$static_var.index}{$parent.path_name}" <[if:$parent.focus]>class="focus"<[/if]>>{$parent.menu_name}</a></li>
	<[/loop]>
	</ul>
</div>

<div id="menu_content">
	<ul>
	<[loop:$admin_menu_list.children as $child]>
		<li>
			<a href="{$static_var.index}{$child.path_name}" <[if:$child.focus]>class="focus"<[/if]>>
			<span>{$child.menu_name}</span>
			</a>
		</li>
	<[/loop]>
	</ul>
</div>