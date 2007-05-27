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
		if(isset($this->stack['static_var']['blog_name']))
		{
			$this->stack['static_var']['blog_name'] = $val['post_title'].' &raquo; '.$this->stack['static_var']['blog_name'];
		}
		if(isset($this->stack['static_var']['keywords']) && $val['post_tags'])
		{
			$this->stack['static_var']['keywords'] = $this->stack['static_var']['keywords'].','.$val['post_tags'];
		}
		
		$val["post_time"] = 
		mgDate($this->stack['static_var']['post_date_format'],$this->stack['static_var']['time_zone'] - $val["post_gmt"],$val["post_time"]);
		$val["post_tags"] = $val["post_tags"] ? explode(",",$val["post_tags"]) : array();
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
