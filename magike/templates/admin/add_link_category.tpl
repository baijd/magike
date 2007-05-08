<[include:header]>
<[include:menu]>

<div id="content">
	<div id="element">
		<div class="proc">
			正在处理您的请求
		</div>
	<form method="post" id="insert_link_category" action="{$static_var.index}/admin/links/link_category_list/?<[if:$insert_category]>c={$insert_category.id}&do=update<[/if]><[if:!$insert_category]>do=insert<[/if]>">
		<h2>增加或编辑链接分类 <span class="discribe">(您可以在这里操作链接分类)</span></h2>
		<div class="input">
			<h2>分类名称</h2>
			<p>
				<input type="text" class="text validate-me" name="link_category_name" value="{$insert_category.category_name}" size=60 /><span class="validate-word" id="link_category_name-word"></span><br />
				<span class="discribe">(为您的链接分类分配一个名称,比如:我的好友)</span>
			</p>
		</div>
		<div class="input">
			<h2>分类描述</h2>
			<p>
				<input type="text" class="text" name="link_category_describe" value="{$insert_category.category_describe}" size=60 /><br />
				<span class="discribe">(描述您所建立的链接分类,比如:这里都是我的好友)</span>
			</p>
		</div>
		<div class="input">
			<h2>是否隐藏</h2>
			<p>
				<input type="radio" name="link_category_hide" value="1" />是 <input type="radio" name="link_category_hide" value="0" />否<span class="validate-word" id="link_category_hide-word"></span><br />
				<span class="discribe">(请选择这个分类是否隐藏)</span>
			</p>
		</div>
		<div class="input">
			<h2>排序方式</h2>
			<p>
				<select name="link_category_linksort" class="validate-me">
					<option value="asc">升序</option>
					<option value="desc">降序</option>
					<option value="rand">随机排序</option>
				</select><span class="validate-word" id="link_category_hide-word"></span><br />
				<span class="discribe">(为您的分类指定一个URL名称,比如:life.请使用英文字母,数字,下划线等,不要使用宽位字符)</span>
			</p>
		</div>
		<div class="submit">
			<span class="button" onclick="magikeValidator('{$static_var.index}/helper/validator/','add_link_category');">提交信息</span>
			<script>
				function validateSuccess()
				{
					document.getElementById('insert_link_category').submit();
				}
			</script>
		</div>
	</form>
	</div>
</div>
<[include:footer]>