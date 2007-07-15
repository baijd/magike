<[include:header]>
<[include:menu]>

<[module:admin_index]>
<[module:posts?sub=20&striptags=1&limit=5]>
<[module:comments_list_all?limit=5&striptags=1&substr=20]>
<[module:get_current_user]>
<style>
	#element
	{
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
		margin:0 255px;
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
		border-bottom:none;
		margin:0;
		height:24px;
		line-height:24px;
		padding:0 0 0 7px;
		background:url({$static_var.siteurl}/templates/{$static_var.admin_template}/images/button.gif) top repeat-x;
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
		height:22px;
		background:#EEF0F2;
		border-bottom:#BEC9D1 solid 1px;
		border-top:#FFF solid 1px;
		overflow:hidden;
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
				<li><u>文章总数</u> <a href="{$static_var.index}/admin/posts/all/">{$static_var.count_posts}篇</a></li>
				<li><u>回响总数</u> <a href="{$static_var.index}/admin/comments/all/">{$static_var.count_comments}则</a></li>
			</ul>
			<h2>快速链接&raquo;</h2>
			<ul class="info">
				<li><a href="{$static_var.index}/admin/posts/write/">撰写一篇文章</a></li>
				<li><a href="{$static_var.index}/admin/skins/skin/">更改我的博客皮肤</a></li>
				<li><a href="{$static_var.index}/admin/settings/">配置我的网站</a></li>
				<li><a href="{$static_var.index}/admin/links/link/">增加一个友情链接</a></li>
			</ul>
		</div>
		<div id="element-right">
			<h2>关于我</h2>
			<ul class="info">
				<li><u>用户名</u> {$get_current_user.user_name}</li>
				<li><u>昵称</u> {$get_current_user.user_nick}</li>
				<li><u>注册时间</u> {$get_current_user.user_register}</li>
				<li><a href="{$static_var.index}/admin/users/user/?user_id={$access.user_id}">编辑我的档案</a></li>
			</ul>
			<h2>新闻&raquo;</h2>
			<ul class="info">
			</ul>
		</div>
		<div id="element-center">
			<h2>我最近发表的文章</h2>
			<ul class="info">
			<[loop:$posts AS $post]>
				<li><a href="{$static_var.index}/admin/posts/write/?post_id={$post.post_id}" title="{$post.post_title}">{$post.post_title}</a> 发布在<a class="close" href="{$static_var.index}/admin/posts/category?c={$post.category_id}">{$post.category_name}</a>  <span class="describe">{$post.post_time}</span></li>
			<[/loop]>
			</ul>
			<h2>最新评论</h2>
			<ul class="info">
			<[loop:$comments_list_all AS $comment]>
				<li>{$comment.comment_user} 在 <a href="{$static_var.index}/admin/posts/write/?post_id={$comment.post_id}">{$comment.post_title}</a> <span class="describe">{$comment.comment_text}</span></li>
			<[/loop]>
			</ul>
		</div>
	</div>
</div>

<[include:footer]>
