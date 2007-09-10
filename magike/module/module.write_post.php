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
		parent::__construct();
		$this->post = $this->loadModel('posts');
	}
	
	public function prasePost($val)
	{
		return $val;
	}
	
	public function runModule()
	{
		if(isset($_GET['post_id']))
		{
			$result = $this->post->fetchPostById($_GET['post_id']);
			
			if($this->stack['access']['user_group'] > $this->stack['static_var']['group']['editor'] && $this->stack['access']['user_id'] != $result["user_id"])
			{
				$this->throwException(E_ACCESSDENIED,$this->stack['action']['path']);
			}
			//修改菜单的内容
			if(isset($this->stack['admin_menu_list']['children']))
			{
				$this->stack['admin_menu_list']['children'][0]['menu_name'] = '编辑 "'.$result['post_title'].'"';
				$this->stack['admin_menu_list']['children'][0]['path_name'] = '/admin/posts/write/?post_id='.$result['post_id'];
				$this->stack['static_var']['admin_title'] = '编辑 "'.$result['post_title'].'"';
			}
			
			$result['post_content'] = preg_replace("/\s*<p>/is","",$result['post_content']);
			$result['post_content'] = preg_replace("/\s*<\/p>\s*/is","\n\n",$result['post_content']);
			$result['post_content'] = trim($result['post_content']);
			
			$result['do'] = 'update';
			return $result;
		}
		else
		{
			return array(
				'do'				 => 'insert',
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
