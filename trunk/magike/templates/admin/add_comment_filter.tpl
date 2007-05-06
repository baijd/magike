<[include:header]>
<[include:menu]>

<[module:comment_all_filters]>
<[module:insert_comment_filter]>
<div id="content">
	<div id="element">
		<div class="proc">
			正在处理您的请求
		</div>
	<form method="post" id="insert_comment_filter" action="{$static_var.index}/admin/comments/filters_list/?<[if:$insert_comment_filter.do == "update"]>cf_id={$insert_comment_filter.id}&do=update<[/if]><[if:$insert_comment_filter.do == "insert"]>do=insert<[/if]>">
		<h2>增加或编辑过滤器 <span class="discribe">(回响过滤器能让您远离垃圾回响的骚扰)</span></h2>
		<div class="input">
			<h2>过滤器名称</h2>
			<p>
				<select name="comment_filter_name" class="validate-me">
				<[loop:$comment_all_filters AS $filter]>
					<option value="{$filter.filter_name}" <[if:$filter.filter_name == $insert_comment_filter.comment_filter_name]>seleted = true<[/if]>>{$filter.filter_lang}</option>
				<[/loop]>
				</select><span class="validate-word" id="comment_filter_name-word"></span><br />
				<span class="discribe">(请选择一个过滤器)</span>
			</p>
		</div>
		<div class="input">
			<h2>过滤器参数</h2>
			<p>
				<textarea class="text" name="comment_filter_value" cols=60 rows=5 >{$insert_comment_filter.comment_filter_value}</textarea><br />
				<span class="discribe">(为您的过滤器配置参数,具体规则还要视不同的类型而定)</span>
			</p>
		</div>
		<div class="input">
			<h2>作用范围</h2>
			<p>
				<input type="radio" name="comment_filter_type" class="validate-me" value="comment" <[if:"comment" == $insert_comment_filter.comment_filter_type]>checked = true<[/if]>/>仅作用于评论 
				<input type="radio" name="comment_filter_type" class="validate-me" value="ping" <[if:"ping" == $insert_comment_filter.comment_filter_type]>checked = true<[/if]>/>仅作用于引用通告 
				<input type="radio" name="comment_filter_type" class="validate-me" value="all" <[if:"all" == $insert_comment_filter.comment_filter_type]>checked = true<[/if]>/>全部适用 <span class="validate-word" id="comment_filter_type-word"></span><br />
				<span class="discribe">(为您的过滤器配置参数,具体规则还要视不同的类型而定)</span>
			</p>
		</div>
		<div class="submit">
			<span class="button" onclick="magikeValidator('{$static_var.index}/helper/validator/','add_comment_filter');">提交信息</span>
			<script>
				function validateSuccess()
				{
					document.getElementById('insert_comment_filter').submit();
				}
			</script>
		</div>
	</form>
	</div>
</div>
<[include:footer]>