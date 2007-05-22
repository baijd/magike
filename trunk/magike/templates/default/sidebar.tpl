<[module:categories_list]>
<[module:recent_posts]>
<[module:comments_list_all?striptags=1&substr=20]>
	<!--rightpart-->
			<div class="rightcon">
				<div class="riblock">
					<div class="rititle">
						<div class="rititleicon"></div>
						Category
					</div>
					<div class="rilist">
						<ul>
							<[loop:$categories_list AS $category]>
							<li><a href="{$static_var.index}/category/{$category.category_postname}/" title="{$category.category_describe}">{$category.category_name}</a></li>
							<[/loop]>
						</ul>
					</div>
				</div>
				<div class="riblock">
					<div class="rititle">
						<div class="rititleicon"></div>
						Recent Posts
					</div>
					<div class="rilist">
						<ul>
							<[loop:$recent_posts AS $post]>
							<li><a href="{$static_var.index}/archives/{$post.post_id}/" title="{$post.post_title}">{$post.post_title}</a></li>
							<[/loop]>
						</ul>
					</div>
				</div>
				<div class="riblock">
					<div class="rititle">
						<div class="rititleicon"></div>
						Recent Comments
					</div>
					<div class="rilist">
						<ul>
							<[loop:$comments_list_all AS $comment]>
							<li><a href="{$static_var.index}/archives/{$comment.post_id}/">{$comment.comment_user}: {$comment.comment_text}</a></li>
							<[/loop]>
						</ul>
					</div>
				</div>
			</div>

			<!--rightpartend-->