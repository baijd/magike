<section:module content="admin_menu_list"/>
<div id="top_menu">
	<ul>
	<section:loop content="$admin_menu_list.parents as $parent">
		<li><a href="{$static_var.index}{$parent.path_name}" <section:if content="$parent.focus">class="focus"</section:if>>{$parent.menu_name}</a></li>
	</section:loop>
	</ul>
</div>
<div id="menu_content">
	<ul>
	<section:loop content="$admin_menu_list.children as $child">
		<li>
			<a href="{$static_var.index}{$child.path_name}" <section:if content="$child.focus">class="focus"</section:if>>
			{$child.menu_name}
			</a>
		</li>
	</section:loop>
	</ul>
</div>