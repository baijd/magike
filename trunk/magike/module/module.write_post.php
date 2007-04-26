<?php
/**********************************
 * Created on: 2007-3-6
 * File Name : module.write_post.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class WritePost extends MagikeModule
{
	function __construct()
	{
		parent::__construct(array('public' => array('database')));
	}
	
	public function prasePost($val)
	{
		return $val;
	}
	
	public function runModule()
	{
		if(isset($_GET['post_id']))
		{
			$args = array('fields'=> '*,table.posts.id AS post_id',
						  'table' => 'table.posts JOIN table.categories ON table.posts.category_id = table.categories.id',
						  'groupby' => 'table.posts.id',
						  );
			$args['where']['template'] = 'table.posts.id = ?';
			$args['where']['value'] = array($_GET['post_id']);
			$result = $this->database->fectch($args,array('function' => array($this,'prasePost')));

			//修改菜单的内容
			if(isset($this->stack['admin_menu_list']['children']))
			{
				$this->stack['admin_menu_list']['children'][0]['menu_name'] = '编辑 "'.$result[0]['post_title'].'"';
				$this->stack['admin_menu_list']['children'][0]['path_name'] = '/admin/posts/write?post_id='.$result[0]['post_id'];
			}
			return $result[0];
		}
		else
		{
			return array(
				'category_id'		 => 0,
				'post_user_name'	 => NULL,
				'post_allow_ping'	 => 1,
				'post_allow_comment' => 1,
				'post_allow_feed'	 => 1,
				'post_is_draft'		 => 0,
				'post_is_hidden'	 => 0,
				'post_is_page'		 => 0
			);
		}
	}
}
?>
