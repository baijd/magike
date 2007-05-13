<[include:header]>
<[include:menu]>

<[module:languages_list]>
<div id="content">
	<div id="element">
		<div class="proc">
			正在处理您的请求
		</div>
	<form method="post" id="insert_path" action="{$static_var.index}/admin/paths/paths_list/?<[if:$insert_path.do == "update"]>path_id={$insert_path.id}&do=update<<[/if]>><[if:$insert_path.do == "insert"]>do=insert<<[/if]>>">
		<h2>全局设置 <span class="discribe">(调整您网站的全局配置)</span></h2>
		<div class="input">
			<h2>站点名称</h2>
			<p>
				<input type="text" class="text validate-me" name="blog_name" value="{$static_var.blog_name}" size=60 /><span class="validate-word" id="path_describe-word"></span><br />
				<span class="discribe">(描述这个路径,比如:网站主页)</span>
			</p>
		</div>
		<div class="input">
			<h2>站点描述</h2>
			<p>
				<input type="text" class="text validate-me" name="describe" value="{$static_var.describe}" size=60 /><span class="validate-word" id="path_name-word"></span><br />
				<span class="discribe">(指向这个路径的地址,详细设置请参考帮助信息)</span>
			</p>
		</div>
		<div class="input">
			<h2>网站地址</h2>
			<p>
				<input type="text" class="text validate-me" name="siteurl" value="{$static_var.siteurl}" size=60 /><span class="validate-word" id="path_file-word"></span><br />
				<span class="discribe">(虚拟路径的解析地址,详细设置请参考帮助信息)</span>
			</p>
		</div>
		<div class="input">
			<h2>关键字</h2>
			<p>
				<input type="text" class="text validate-me" name="keywords" value="{$static_var.keywords}" size=60 /><span class="validate-word" id="path_file-word"></span><br />
				<span class="discribe">(虚拟路径的解析地址,详细设置请参考帮助信息)</span>
			</p>
		</div>
		<div class="input">
			<h2>静态链接</h2>
			<p>
				<input type="radio" name="index" value="{$static_var.siteurl}" <[if:$static_var.index == $static_var.siteurl]>checked=true<[/if]>/>是 
				<input type="radio" name="index" value="{$static_var.siteurl}/index.php" <[if:$static_var.index != $static_var.siteurl]>checked=true<[/if]>/>否<span class="validate-word" id="path_file-word"></span><br />
				<span class="discribe">(虚拟路径的解析地址,详细设置请参考帮助信息)</span>
			</p>
		</div>
		<div class="input">
			<h2>语言</h2>
			<p>
				<select name="language" class="validate-me">
				<[loop:$languages_list AS $lang]>
					<option value="{$lang}" <[if:$static_var.language == $lang]>selected=true<[/if]>>{$lang}</option>
				<[/loop]>
				</select><span class="validate-word" id="path_action-word"></span><br />
				<span class="discribe">(为这个路径选择一个解析器)</span>
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
				<span class="validate-word" id="user_group-word"></span><span class="discribe">(选择您当前所在的时区)</span>
			</p>
		</div>
		<div class="submit">
			<span class="button" onclick="magikeValidator('{$static_var.index}/helper/validator/','add_path');">提交信息</span>
			<script>
				function validateSuccess()
				{
					document.getElementById('insert_path').submit();
				}
			</script>
		</div>
	</form>
	</div>
</div>
<[include:footer]>
