<[include:header]>
<[include:menu]>

<[module:insert_link]>
<[module:link_categories]>
<div id="content">
	<div id="element">
	<div class="proc">
		正在处理您的请求
	</div>
	<form method="post" id="insert_link" action="{$static_var.index}/admin/links/link_list/?<[if:$insert_link.do == "update"]>link_id={$insert_link.id}&do=update<[/if]><[if:$insert_link.do == "insert"]>do=insert<[/if]>">
	<h2>新增或编辑分类 <span class="discribe">(您可以在此编辑分类)</span></h2>
	<div class="input">
		<h2>链接名称</h2>
		<p>
			<input type="text" class="text validate-me" value="{$insert_link.link_name}" name="link_name" size=60 /><span class="validate-word" id="link_name-word"></span><br />
			<span class="discribe">(为您的链接分配一个名称,比如:皮皮鲁的站点)</span>
		</p>
	</div>
	<div class="input">
		<h2>链接地址</h2>
		<p>
			<input type="text" class="text validate-me" value="{$insert_link.link_url}" name="link_url" size=60 /><span class="validate-word" id="link_url-word"></span><br />
			<span class="discribe">(为您的链接分配一个地址,比如:http://www.magike.net)</span>
		</p>
	</div>
	<div class="input">
		<h2>链接图片</h2>
		<p>
			<input type="text" class="text" value="{$insert_link.link_image}" name="link_image" size=60 /><br />
			<span class="discribe">(为您的链接加上一副图片,比如:http://www.magike.net/images/logo.gif)</span>
		</p>
	</div>
	<div class="input">
		<h2>描述</h2>
		<p>
			<input type="text" class="text" value="{$insert_link.link_describe}" name="link_describe" size=60 /><br />
			<span class="discribe">(描述这个链接,比如:他是我的好朋友)</span>
		</p>
	</div>
	<div class="input">
		<h2>链接分类</h2>
		<p>
			<select name="link_category_id" class="validate-me">
				<[loop:$link_categories AS $link_category]>
					<option value="{$link_category.id}" <[if:$insert_link.link_category_id == $link_category.id]>selected=true<[/if]>>{$link_category.link_category_name}</option>
				<[/loop]>
			</select><span class="validate-word" id="link_category_id-word"></span><br />
			<span class="discribe">(为这个链接分配一个分类)</span>
		</p>
	</div>
	<div class="submit">
		<span class="button" onclick="magikeValidator('{$static_var.index}/helper/validator/','add_link');">提交信息</span>
		<script>
			function validateSuccess()
			{
				document.getElementById('insert_link').submit();
			}
		</script>
	</div>
	</div>
</div>

<[include:footer]>