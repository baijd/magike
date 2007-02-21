<section:include content="header"/>
<section:include content="menu"/>

<section:module content="categories_list"/>
<div id="content">
	<div id="element">
		<h2>{lang.admin_posts_list.list_title} <span class="discribe">{lang.admin_posts_list.list_describe}</span></h2>
		<p>
			<div id="db_table">
			</div>
			<div id="db_info">
			</div>
			<div id="db_table_nav">
				<span id="magike_db_grid_select_all">{lang.admin_db_grid.select_all}</span> , 
				<span id="magike_db_grid_select_none">{lang.admin_db_grid.select_none}</span> , 
				<span id="magike_db_grid_select_other">{lang.admin_db_grid.select_other}</span> , 
				<span id="magike_db_grid_select_category">{lang.admin_db_grid.select_category}</span>
			</div>
			<div id="db_table_category">
				{lang.admin_posts_list.select_category} 
				<select id="magike_db_grid_select_category_list">
				<section:loop content="$categories_list as $admin_category">
					<option value="{$admin_category.category_name}">{$admin_category.category_name}</option>
				</section:loop>
				</select> 
				<span id="magike_db_grid_select_category_choose">
					选定
				</span>
			</div>
			<script>
					magikeDbGrid.init("{$static_var.index}/admin/posts/all/1",
									 "{$static_var.index}/admin/posts/all/info",
									 {
									 "selector" : {"text" : "&nbsp;","width" : "5%"},
									 "post_title" : {"text" : "标题","width" : "45%","click" : true,"class" : "post_title"},
									 "category_name" : {"text" : "分类","width" : "30%","select" : true},
									 "post_time" : {"text" : "时间","width" : "20%","class" : "date"}
									 },
									 "post_id",
									 20,
									 "db_table",
									 "post_content",
									 "db_table_nav",
									 "db_info",
									 "db_table_category"
									 );
			</script>
		</p>
	</div>
</div>

<section:include content="footer"/>