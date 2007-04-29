<[include:header]>
<[include:menu]>

<[module:admin_index]>
<style>
	#element
	{
		margin:0 auto;
		text-align:left;
	}

	#element-left
	{
		float:left;
		width:250px;
		margin-right:-250px;
	}
	
	#element-center
	{
		margin:0 255px !important;
		margin:0 253px;
		width:auto;
	}
	
	#element-right
	{
		float:right;
		width:250px;
		margin-left:-250px;
	}
	
	#element h2
	{
		border-top:1px solid #CCC;
		border-bottom:none;
		margin:0;
		background:url({$static_var.siteurl}/templates/{$static_var.admin_template}/images/top.gif) top repeat-x;
	}
	
	#element ul.info
	{
		margin:0;
		margin-bottom:20px;
		padding:0;
	}
	
	#element ul.info li
	{
		list-style:none;
		font-size:9pt;
		padding:0 0 0 7px;
		margin:0;
		color:#444;
		line-height:22px;
	}
	
	#element ul.info li.alt
	{
		background:#EEE;
	}
	
	#element u
	{
		padding-left:12px;
		background:url({$static_var.siteurl}/templates/{$static_var.admin_template}/images/ufolder.gif) left top no-repeat;
		text-decoration: none;
		color:#777;
	}
	
	#element ul.info li a
	{
		color:#444;
		padding-right:12px;
		background:url({$static_var.siteurl}/templates/{$static_var.admin_template}/images/folder.gif) right top no-repeat;
	}
	
	#element ul.info li a.close
	{
		background:none;
		padding:0;
	}
	
	#element ul.info li a:hover
	{
		text-decoration: none;
	}
</style>
<div id="content">
	<div id="element">
		<div id="element-left">
			<h2>{lang.admin_index.global_runtime}</h2>
			<ul class="info">
				<li><u>{lang.admin_index.server_version}</u> {$admin_index.server_version}</li>
				<li><u>{lang.admin_index.database_version}</u> {$admin_index.database_version}</li>
				<li><u>{lang.admin_index.magike_version}</u> {$admin_index.magike_version}</li>
			</ul>
			<h2>快速链接&raquo;</h2>
			<ul class="info">
				<li><a href="{$static_var.index}/admin/posts/write">撰写一篇文章</a></li>
				<li><a href="#">更改我的博客皮肤</a></li>
				<li><a href="#">编辑我的档案</a></li>
				<li><a href="#">配置我的网站</a></li>
				<li><a href="#">增加一个友情链接</a></li>
			</ul>
		</div>
		<div id="element-right">
			<h2>{lang.admin_index.global_runtime}</h2>
			<ul class="info">
				<li><u>{lang.admin_index.server_version}</u> {$admin_index.server_version}</li>
				<li><u>{lang.admin_index.database_version}</u> {$admin_index.database_version}</li>
				<li><u>{lang.admin_index.magike_version}</u> {$admin_index.magike_version}</li>
			</ul>
			<h2>快速链接&raquo;</h2>
			<ul class="info">
				<li><a href="{$static_var.index}/admin/posts/write">撰写一篇文章</a></li>
				<li><a href="#">更改我的博客皮肤</a></li>
				<li><a href="#">编辑我的档案</a></li>
				<li><a href="#">配置我的网站</a></li>
				<li><a href="#">增加一个友情链接</a></li>
			</ul>
		</div>
		<div id="element-center">
						<h2>我最近发表的文章</h2>
			<ul class="info">
				<li><a href="#">Magike 1.0发布了!</a> 发布在<a class="close" href="#">程序发布</a>  2天前</li>
				<li><a href="#">郁闷的生活</a> 发布在<a class="close" href="#">生活杂谈</a> 2天前</li>
				<li><a href="#">我搜集的一些小图标</a> 发布在<a class="close" href="#">网络天下</a> 3天前</li>
				<li><a href="#">一个有趣的站点</a> 发布在<a class="close" href="#">网络天下</a> 4天前</li>
				<li><a href="#">我搜集的一些小图标</a> 发布在<a class="close" href="#">网络天下</a> 3天前</li>
			</ul>
			<h2>快速链接&raquo;</h2>
			<ul class="info">
				<li><a href="{$static_var.index}/admin/posts/write">撰写一篇文章</a></li>
				<li><a href="#">更改我的博客皮肤</a></li>
				<li><a href="#">编辑我的档案</a></li>
				<li><a href="#">配置我的网站</a></li>
				<li><a href="#">增加一个友情链接</a></li>
			</ul>
		</div>
	</div>
</div>

<[include:footer]>
