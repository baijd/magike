<[include:header]>
<[include:menu]>

<[module:admin_index]>
<[module:posts.is_admin?striptags=1&limit=10 AS posts]>
<[module:comments.is_recent?limit=10&striptags=1&substr=30 AS comments]>
<[module:get_current_user]>
<style>
	#element
	{
		text-align:left;
	}
	
	#element-center
	{
		margin:0 255px 0 0;
		width:auto;
	}
	
	#element-right
	{
		float:right;
		width:250px;
		margin-left:-250px;
		background:#292D34;
		border:1px solid #222;
	}
	
	#element-right h2,#element-center h2
	{
		margin:0;
		height:24px;
		line-height:24px;
		padding:0 0 0 7px;
	}
	
	#element-center h2
	{
		border-bottom:none;
		background:url({$static_var.siteurl}/templates/{$static_var.admin_template}/images/button.gif) top repeat-x;
	}
	
	#element-right h2
	{
		border-bottom:1px solid #222;
		color:#CCC;
	}
	
	#element-right ul.info
	{
		border-top:1px solid #5B686F;
	}
	
	#element ul.info
	{
		margin:0;
		padding:0;
	}
	
	#element ul.info li
	{
		list-style:none;
		font-size:9pt;
		padding:0 0 0 7px;
		margin:0;
		color:#AAA;
		line-height:24px;
		height:24px;
		overflow:hidden;
	}
	
	#element u
	{
		text-decoration: none;
		color:#AAA;
	}
	
	#element ul.info li a
	{
		color:#AAA;
		padding-right:2px;
	}
	
	#element-center ul.info li
	{
		line-height:24px;
		height:24px;
		color:#444;
	}
	
	#element-center ul.info li.alt
	{
		background:#F4F4F4;
		border-bottom:1px solid #E4E4E4;
	}
	
	#element-center u
	{
		text-decoration: none;
		color:#777;
	}
	
	#element-center ul.info li a
	{
		color:#444;
		padding-right:2px;
	}
	
	#element ul.info li a:hover
	{
		text-decoration: none;
	}
	
	.element-info
	{
		margin-bottom:10px;
	}
</style>
<div id="content">
	<div id="element">
		<div id="element-right">
			<div class="element-info">
			<h2>关于我</h2>
			<ul class="info">
				<li><u>用户名</u> {$get_current_user.user_name}</li>
				<li><u>昵称</u> {$get_current_user.user_nick}</li>
				<li><u>注册时间</u> {$get_current_user.user_register}</li>
				<li><a href="{$static_var.index}/admin/users/user/?user_id={$access.user_id}">编辑我的档案</a></li>
			</ul>
			</div>
			<div class="element-info">
			<h2>{lang.admin_index.global_runtime}</h2>
			<ul class="info">
				<li><u>{lang.admin_index.server_version}</u> {$admin_index.server_version}</li>
				<li><u>{lang.admin_index.database_version}</u> {$admin_index.database_version}</li>
				<li><u>{lang.admin_index.magike_version}</u> {$admin_index.magike_version}</li>
				<li><u>文章总数</u> <a href="{$static_var.index}/admin/posts/all/">{$static_var.count_posts}篇</a></li>
				<li><u>回响总数</u> <a href="{$static_var.index}/admin/comments/all/">{$static_var.count_comments}则</a></li>
			</ul>
			</div>
			<div class="element-info">
			<h2>管理工具&raquo;</h2>
			<ul class="info">
				<li><a href="#">清空所有缓存</a></li>
				<li><a href="#">优化数据库</a></li>
				<li><a href="#">检查升级</a></li>
			</ul>
			</div>
			<div class="element-info">
			<h2>快速链接&raquo;</h2>
			<ul class="info">
				<li><a href="{$static_var.index}/admin/posts/write/">撰写一篇文章</a></li>
				<li><a href="{$static_var.index}/admin/skins/skin/">更改我的博客皮肤</a></li>
				<li><a href="{$static_var.index}/admin/settings/">配置我的网站</a></li>
				<li><a href="{$static_var.index}/admin/links/link/">增加一个友情链接</a></li>
			</ul>
			</div>
		</div>
		<div id="element-center">
			<div class="element-info">
			<h2>我最近发表的文章</h2>
			<ul class="info">
			<[loop:$posts AS $post]>
				<li <[if:$post.post_alt]>class="alt"<[/if]>><a href="{$static_var.index}/admin/posts/write/?post_id={$post.post_id}" title="{$post.post_title}">{$post.post_title}</a> 发布在<a class="close" href="{$static_var.index}/admin/posts/category?c={$post.category_id}">{$post.category_name}</a>  <span class="describe">{$post.post_time}</span></li>
			<[/loop]>
			</ul>
			</div>
			<div class="element-info">
			<h2>最新评论</h2>
			<ul class="info">
			<[loop:$comments AS $comment]>
				<li <[if:$comment.comment_alt]>class="alt"<[/if]>>{$comment.comment_user} 在 <a href="{$static_var.index}/admin/posts/write/?post_id={$comment.post_id}">{$comment.post_title}</a> <span class="describe">{$comment.comment_text}</span></li>
			<[/loop]>
			</ul>
			</div>
		</div>
	</div>
</div>

<[include:footer]>
