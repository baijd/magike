[include:header]
[include:menu]

<div id="content">
	<div id="element">
		<h2>{lang.admin_posts_list.list_title} <span class="discribe">{lang.admin_posts_list.list_describe}</span></h2>
		<p>
			<div id="db_table">
			</div>
			<div id="db_table_nav">
				<span id="magike_db_grid_select_all">{lang.admin_db_grid.select_all}</span> , 
				<span id="magike_db_grid_select_none">{lang.admin_db_grid.select_none}</span> , 
				<span id="magike_db_grid_select_other">{lang.admin_db_grid.select_other}</span>
			</div>
			<script>
					magikeDbGrid.init("{$static.index}/admin/posts/all/1",
									 "{$static.index}/admin/posts/all/info",
									 {
									 "selector" : {"text" : "&nbsp;","width" : "5%"},
									 "post_title" : {"text" : "标题","width" : "45%","click" : true,"class" : "post_title"},
									 "category_name" : {"text" : "分类","width" : "30%"},
									 "post_time" : {"text" : "时间","width" : "20%","class" : "date"}
									 },
									 "db_table",
									 "post_id",
									 "db_table_nav"
									 );
			</script>
		</p>
	</div>
</div>

[include:footer]