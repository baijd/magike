<[include:header]>
<[include:menu]>

<style>
#element h2
{
	border-bottom:none;
}
#element-left
{
	float:left;
	width:298px;
	margin-right:-250px;
}

.now
{
	padding:9px;
	border:1px solid #CCC;
	background:#EEE;
}

.now img
{
	border:5px solid #AAA;
}

#element-right
{
	margin:0 0 0 315px;
	width:auto;
}

#element ul
{
	margin:0;
	padding:0;
}

#element ul li
{
	list-style:none;
	margin:0;
	padding:10px;
	background:url({$static_var.siteurl}/{!__TEMPLATE__}/{$static_var.admin_template}/images/top.gif) top repeat-x;
	border-top:1px solid #CCC;
	height:120px;
	font-size:9pt;
}

#element ul li img
{
	border:2px solid #CCC;
	margin-right:10px;
}
</style>

<[module:skin_input]>
<[module:skins_list]>
<div id="content">
	<div id="element">
	<[if:$skin_input.open]>
		<div class="message">
			{$skin_input.word}
		</div>
	<[/if]>
		<div id="element-left">
			<h2>当前风格 <span class="discribe">(这是当前应用在您网站的风格)</span></h2>
			<div class="now"><img src="{$static_var.siteurl}/{!__TEMPLATE__}/{$static_var.template}/screen.jpg" /></div>
		</div>
		<div id="element-right">
			<h2>风格列表 <span class="discribe">(选择一个风格,应用到您的网站)</span></h2>
			<ul>
			<[loop:$skins_list AS $skin]>
				<li><a href="{$static_var.index}/admin/skins/skins_list/?tpl={$skin.template}"><img src="{$static_var.siteurl}/{!__TEMPLATE__}/{$skin.template}/screen.jpg" align=left height=100 /></a>{$skin.readme}</li>
			<[/loop]>
			</ul>
		</div>
	</div>
</div>

<[include:footer]>
