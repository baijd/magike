<[include:header]>
<[include:menu]>

<div id="content">
	<div id="element">
		<div class="proc">
			正在处理您的请求
		</div>
	<form method="post" name="insert_category" id="insert_category" action="{$static_var.index}/admin/posts/categories_list/?<[if:$insert_category]>c={$insert_category.id}&act=update<[/if]><[if:!$insert_category]>act=insert<[/if]>">
		<h2>增加或编辑过滤器 <span class="discribe">(回响过滤器能让您远离垃圾回响的骚扰)</span></h2>
		<div class="input">
			<h2>过滤器名称</h2>
			<p>
				<input type="text" class="text validate-me" name="category_name" value="{$insert_category.category_name}" size=60 /><span class="validate-word" id="category_name-word"></span><br />
				<span class="discribe">(请选择一个过滤器)</span>
			</p>
		</div>
		<div class="input">
			<h2>过滤器参数</h2>
			<p>
				<textarea class="text" name="post_trackback" cols=60 rows=5 ></textarea><span class="validate-word" id="category_postname-word"></span><br />
				<span class="discribe">(为您的过滤器配置参数,具体规则还要视不同的类型而定)</span>
			</p>
		</div>
		<div class="submit">
			<span class="button" onclick="magikeValidator('{$static_var.index}/helper/validator/','add_category');">提交信息</span>
			<script>
				function validateSuccess()
				{
					document.getElementById('insert_category').submit();
				}
			</script>
		</div>
	</form>
	</div>
</div>
<[include:footer]>