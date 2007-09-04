<[include:header]>
<[include:menu]>

<[module:setting_permalink]>
<[module:setting_permalink_input]>
<style>
strong.description
{
	margin:auto 10px;
	color:#999;
	font-weight:normal;
	height:30px;
	line-height:30px;
	font-size:9pt;
	font-family:Trebuchet MS,verdana, Helvetica, sans-serif;
}

strong.title
{
	font-family:Trebuchet MS,verdana, Helvetica, sans-serif;
	float:none;
	width:auto;
	font-size:12pt;
	color:#5B686F;
	height:30px;
	line-height:30px;
	margin:auto 10px;
}

strong a
{
	color:#222;
}

strong a:hover
{
	text-decoration:none;
}

#content #element input
{
	float:right;
	margin-top:-40px;
	margin-right:40px;
}
</style>
<div id="content">
	<div id="element">
	<[if:$setting_permalink_input.open]>
		<div class="message">
			{$setting_permalink_input.word}
		</div>
	<[/if]>
		<div class="proc">
			正在处理您的请求
		</div>
		<form method="post" id="setting_permalink">
		<h2>设置静态链接 <span class="discribe">(选择一个静态链接风格)</span></h2>
		<[loop:$setting_permalink AS $permalink]>
		<div class="input" <[if:$permalink.key == $static_var.permalink_style]>style="background:#EEF0F2"<[/if]>>
			{$permalink.word}
			<input type="radio" name="permalink" <[if:$permalink.key == $static_var.permalink_style]>checked=true<[/if]> value="{$permalink.key}" />
		</div>
		<[/loop]>
		<div class="input" style="background:#FFFFAA">
			<strong class="title">了解如何自定义静态链接</strong><br />
			<strong class="description"><a href="#">[?]访问我们的知识库系统以获取信息</a></strong>
		</div>
		<div class="submit">
			<span class="button" onclick="magikeValidator('{$static_var.index}/helper/validator/','setting_permalink');">提交信息</span>
			<input type="hidden" name="do" value="update" />
			<script>
				function validateSuccess()
				{
					document.getElementById('setting_permalink').submit();
				}
				
				$('#setting_permalink').submit(
					function()
					{
						magikeValidator('{$static_var.index}/helper/validator/','setting_permalink');
						return false;
					}
				);
			</script>
		</div>
		</form>
	</div>
</div>
<[include:footer]>