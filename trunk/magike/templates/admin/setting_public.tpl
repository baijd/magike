<[include:header]>
<[include:menu]>

<[module:static_var_input]>
<[module:languages_list]>
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
	<form method="post" id="setting_public">
		<h2>全局设置 <span class="discribe">(调整您网站的全局配置)</span></h2>
		<div class="input">
			<h2>站点名称</h2>
			<p>
				<input type="text" class="text validate-me" name="blog_name" value="{$static_var.blog_name}" size=60 /><span class="validate-word" id="blog_name-word"></span><br />
				<span class="discribe">(您站点的名称,比如:我的个人空间)</span>
			</p>
		</div>
		<div class="input">
			<h2>站点描述</h2>
			<p>
				<input type="text" class="text" name="describe" value="{$static_var.describe}" size=60 /><br />
				<span class="discribe">(描述您的站点,比如:这是我的小窝)</span>
			</p>
		</div>
		<div class="input">
			<h2>网站地址</h2>
			<p>
				<input type="text" class="text validate-me" name="siteurl" value="{$static_var.siteurl}" size=60 /><span class="validate-word" id="siteurl-word"></span><br />
				<span class="discribe">(这是您网站的绝对地址,请不要在末尾加反斜杠)</span>
			</p>
		</div>
		<div class="input">
			<h2>关键字</h2>
			<p>
				<input type="text" class="text" name="keywords" value="{$static_var.keywords}" size=60 /><br />
				<span class="discribe">(为您的网站设置一组关键字,用半角逗号隔开)</span>
			</p>
		</div>
		<div class="input">
			<h2>静态链接</h2>
			<p>
				<input type="radio" name="index" value="{$static_var.siteurl}" <[if:$static_var.index == $static_var.siteurl]>checked=true<[/if]>/>是 
				<input type="radio" name="index" value="{$static_var.siteurl}/index.php" <[if:$static_var.index != $static_var.siteurl]>checked=true<[/if]>/>否<br />
				<span class="discribe"><strong>(注意:打开此选项前请向您的空间服务商确认您的服务器支持rewrite,否则可能遇到严重错误)</strong></span>
			</p>
		</div>
		<div class="input">
			<h2>语言</h2>
			<p>
				<select name="language">
				<[loop:$languages_list AS $lang]>
					<option value="{$lang}" <[if:$static_var.language == $lang]>selected=true<[/if]>>{$lang}</option>
				<[/loop]>
				</select><br />
				<span class="discribe">(选择一个您适用的语言)</span>
			</p>
		</div>
		<div class="input">
			<h2>时区调整</h2>
			<p>
				<select name="time_zone">
					<option value="0" <[if:$static_var.time_zone == "0"]>selected=true<[/if]>>{lang.timezone.timezone_gmt}</option>
					<option value="3600" <[if:$static_var.time_zone == "3600"]>selected=true<[/if]>>{lang.timezone.timezone_gmtz1}</option>
					<option value="7200" <[if:$static_var.time_zone == "7200"]>selected=true<[/if]>>{lang.timezone.timezone_gmtz2}</option>
					<option value="10800" <[if:$static_var.time_zone == "10800"]>selected=true<[/if]>>{lang.timezone.timezone_gmtz3}</option>
					<option value="14400" <[if:$static_var.time_zone == "14400"]>selected=true<[/if]>>{lang.timezone.timezone_gmtz4}</option>
					<option value="18000" <[if:$static_var.time_zone == "18000"]>selected=true<[/if]>>{lang.timezone.timezone_gmtz5}</option>
					<option value="21600" <[if:$static_var.time_zone == "21600"]>selected=true<[/if]>>{lang.timezone.timezone_gmtz6}</option>
					<option value="25200" <[if:$static_var.time_zone == "25200"]>selected=true<[/if]>>{lang.timezone.timezone_gmtz7}</option>
					<option value="28800" <[if:$static_var.time_zone == "28800"]>selected=true<[/if]>>{lang.timezone.timezone_gmtz8}</option>
					<option value="32400" <[if:$static_var.time_zone == "32400"]>selected=true<[/if]>>{lang.timezone.timezone_gmtz9}</option>
					<option value="36000" <[if:$static_var.time_zone == "36000"]>selected=true<[/if]>>{lang.timezone.timezone_gmtz10}</option>
					<option value="39600" <[if:$static_var.time_zone == "39600"]>selected=true<[/if]>>{lang.timezone.timezone_gmtz11}</option>
					<option value="43200" <[if:$static_var.time_zone == "43200"]>selected=true<[/if]>>{lang.timezone.timezone_gmtz12}</option>
					<option value="-3600" <[if:$static_var.time_zone == "-3600"]>selected=true<[/if]>>{lang.timezone.timezone_gmtf1}</option>
					<option value="-7200" <[if:$static_var.time_zone == "-7200"]>selected=true<[/if]>>{lang.timezone.timezone_gmtf2}</option>
					<option value="-10800" <[if:$static_var.time_zone == "-10800"]>selected=true<[/if]>>{lang.timezone.timezone_gmtf3}</option>
					<option value="-14400" <[if:$static_var.time_zone == "-14400"]>selected=true<[/if]>>{lang.timezone.timezone_gmtf4}</option>
					<option value="-18000" <[if:$static_var.time_zone == "-18000"]>selected=true<[/if]>>{lang.timezone.timezone_gmtf5}</option>
					<option value="-21600" <[if:$static_var.time_zone == "-21600"]>selected=true<[/if]>>{lang.timezone.timezone_gmtf6}</option>
					<option value="-25200" <[if:$static_var.time_zone == "-25200"]>selected=true<[/if]>>{lang.timezone.timezone_gmtf7}</option>
					<option value="-28800" <[if:$static_var.time_zone == "-28800"]>selected=true<[/if]>>{lang.timezone.timezone_gmtf8}</option>
					<option value="-32400" <[if:$static_var.time_zone == "-32400"]>selected=true<[/if]>>{lang.timezone.timezone_gmtf9}</option>
					<option value="-36000" <[if:$static_var.time_zone == "-36000"]>selected=true<[/if]>>{lang.timezone.timezone_gmtf10}</option>
					<option value="-39600" <[if:$static_var.time_zone == "-39600"]>selected=true<[/if]>>{lang.timezone.timezone_gmtf11}</option>
					<option value="-43200" <[if:$static_var.time_zone == "-43200"]>selected=true<[/if]>>{lang.timezone.timezone_gmtf12}</option>
				</select><br />
				<span class="discribe">(选择您当前所在的时区)</span>
			</p>
		</div>
		<div class="input">
			<h2>启用防盗链功能</h2>
			<p>
				<select name="referer_denny">
					<option value="1" <[if:$static_var.referer_denny == 1]>selected=true<[/if]>>是</option>
					<option value="0" <[if:$static_var.referer_denny != 1]>selected=true<[/if]>>否</option>
				</select><br />
				<span class="discribe">(防止您网站的资源被其它网站盗用)</span>
			</p>
		</div>
		<div class="submit">
			<span class="button" onclick="magikeValidator('{$static_var.index}/helper/validator/','setting_public');">提交信息</span>
			<input type="hidden" name="do" value="update" />
			<script>
				function validateSuccess()
				{
					document.getElementById('setting_public').submit();
				}
			</script>
		</div>
	</form>
	</div>
</div>
<[include:footer]>
