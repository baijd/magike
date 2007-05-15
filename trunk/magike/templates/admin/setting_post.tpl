<[include:header]>
<[include:menu]>

<[module:static_var_input]>
<div id="content">
	<div id="element">
	<[if:$static_var_input.open]>
		<div class="message">
			{$static_var_input.word}
		</div>
	<[/if]>
		<div class="proc">
			正在处理您的请求
		</div>
	<form method="post" id="setting_post">
		<h2>文章输出设置 <span class="discribe">(调整您的文章输出时的配置)</span></h2>
		<div class="input">
			<h2>文章日期格式</h2>
			<p>
				<input type="text" class="text validate-me" name="post_date_format" value="{$static_var.post_date_format}" size=60 /><span class="validate-word" id="post_date_format-word"></span><br />
				<span class="discribe">(请参考PHP日期格式)</span>
			</p>
		</div>
		<div class="input">
			<h2>每页文章数</h2>
			<p>
				<input type="text" class="text validate-me" name="post_page_num" value="{$static_var.post_page_num}" size=30 /><span class="validate-word" id="post_page_num-word"></span><br />
				<span class="discribe">(分页时每页文章的数目)</span>
			</p>
		</div>
		<div class="input">
			<h2>列表文章数</h2>
			<p>
				<input type="text" class="text validate-me" name="post_list_num" value="{$static_var.post_list_num}" size=30 /><span class="validate-word" id="post_list_num-word"></span><br />
				<span class="discribe">(显示在列表中文章的数目)</span>
			</p>
		</div>
		<div class="input">
			<h2>摘要字数</h2>
			<p>
				<input type="text" class="text validate-me" name="post_sub" value="{$static_var.post_sub}" size=30 /><span class="validate-word" id="post_sub-word"></span><br />
				<span class="discribe">(文章强制摘要的字数,如果为0表示不强制摘要)</span>
			</p>
		</div>
		<div class="input">
			<h2>启用来源统计</h2>
			<p>
				<select name="referer_log">
					<option value="1" <[if:$static_var.referer_log == 1]>selected=true<[/if]>>是</option>
					<option value="0" <[if:$static_var.referer_log != 1]>selected=true<[/if]>>否</option>
				</select><br />
				<span class="discribe">(记录用户从何处点击您的文章)</span>
			</p>
		</div>
		<div class="input">
			<h2>启用访问统计</h2>
			<p>
				<select name="post_log">
					<option value="1" <[if:$static_var.post_log == 1]>selected=true<[/if]>>是</option>
					<option value="0" <[if:$static_var.post_log != 1]>selected=true<[/if]>>否</option>
				</select><br />
				<span class="discribe">(记录每篇文章的访问量)</span>
			</p>
		</div>
		<div class="submit">
			<span class="button" onclick="magikeValidator('{$static_var.index}/helper/validator/','setting_post');">提交信息</span>
			<input type="hidden" name="do" value="update" />
			<script>
				function validateSuccess()
				{
					document.getElementById('setting_post').submit();
				}
			</script>
		</div>
	</form>
	</div>
</div>
<[include:footer]>
