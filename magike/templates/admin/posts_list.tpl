[include:header]
[include:menu]

<div id="content">
	<div id="element">
		<h2>{lang.admin_posts_list.list_title} <span class="discribe">{lang.admin_posts_list.list_describe}</span></h2>
		<p>
			<div id="db_table"></div>
			<script>
					magikeDbGrid.init("{$static.index}/admin/posts/all/1",
									 "{$static.index}/admin/posts/all/info",
									 {
									 "post_id" : {"text" : "选","width" : "5%"},
									 "post_title" : {"text" : "标题","width" : "45%","click" : true,"class" : "post_title"},
									 "category_name" : {"text" : "分类","width" : "30%"},
									 "post_time" : {"text" : "时间","width" : "20%","class" : "date"}
									 },
									 "db_table");
			</script>
		</p>
	</div>
</div>

[include:footer]