<?php
/**********************************
 * Created on: 2007-3-6
 * File Name : module.post.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class Post extends MagikeModule
{
	private $post;
	
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
			return $this->post->fectchPostById($_GET['post_id'],array('function' => array($this,'prasePost')));
		}
		else if(isset($_GET['post_name']))
		{
			return $this->post->fectchPostByName($_GET['post_name'],array('function' => array($this,'prasePost')));
		}
		else
		{
			return array();
		}
	}
}
?>
