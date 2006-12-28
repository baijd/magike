<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : admin_posts_list_module.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
 class AdminPostsListModule extends MagikeModule
 {
	 public runModule()
	 {
		 //初始化查询语句
		 $args = array('table'	 => 'table.posts JOIN table.categories ON table.posts.category_id = table.categories.id',
					   'groupby' => 'table.posts.id',
					   'fields'	 => '*,table.posts.id AS post_id',
					   'orderby' => 'table.posts.post_time',
					  );

		 $args['limit']  = isset($this->stack->data['static']['admin_posts_limit']) ? $this->stack->data['static']['admin_posts_limit'] : 20;
		 $args['offset'] = isset($_GET['page']) ? $_GET['page'] : 0;

		 $this->template->data['admin_posts'] = $this->database->fectch($args);
	 }
 }
 ?>
