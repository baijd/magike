<[include:header]>
<[include:menu]>

<[module:insert_category]>
<div id="content">
	<div id="element">
		<div class="proc">
			正在处理您的请求
		</div>
	<form method="post" id="insert_category" action="{$static_var.index}/admin/posts/categories_list/?<[if:$insert_category]>c={$insert_category.id}&do=update<[/if]><[if:!$insert_category]>do=insert<[/if]>">
		<h2>增加或编辑分类 <span class="discribe">(您可以在这里操作文章分类)</span></h2>
		<div class="input">
			<h2>分类名称</h2>
			<p>
				<input type="text" class="text validate-me" name="category_name" value="{$insert_category.category_name}" size=60 /><span class="validate-word" id="category_name-word"></span><br />
				<span class="discribe">(为您的分类分配一个名称,比如:编程心得)</span>
			</p>
		</div>
		<div class="input">
			<h2>URL名称</h2>
			<p>
				<input type="text" class="text validate-me" name="category_postname" value="{$insert_category.category_postname}" size=60 /><span class="validate-word" id="category_postname-word"></span><br />
				<span class="discribe">(为您的分类指定一个URL名称,比如:life.请使用英文字母,数字,下划线等,不要使用宽位字符)</span>
			</p>
		</div>
		<div class="input">
			<h2>分类描述</h2>
			<p>
				<input type="text" class="text validate-me" name="category_describe" value="{$insert_category.category_describe}" size=60 /><br />
				<span class="discribe">(描述您所建立的分类,如果没有必要,可以留空)</span>
			</p>
		</div>
		<div class="submit">
			<span class="button" onclick="magikeValidator('{$static_var.index}/helper/validator/','add_category');">提交信息</span>
			<script>
				function validateSuccess()
				{
					document.getElementById('insert_category').submit();
				}
				
				$('#insert_category').submit(
					function()
					{
						magikeValidator('{$static_var.index}/helper/validator/','add_category');
						return false;
					}
				);
			</script>
		</div>
	</form>
	</div>
</div>

<[include:footer]>