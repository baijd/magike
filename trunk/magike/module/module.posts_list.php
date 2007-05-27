<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.posts_list.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class PostsList extends MagikeModule
{
	private $result;
	private $getArgs;
	
	public function prasePost($val,$num)
	{
		$val["post_content"] = $this->getArgs["striptags"] ? mgStripTags($val["post_content"]) : $val["post_content"];
		if(!$this->getArgs["content"])
		{
			$post = explode("<!--more-->",$val["post_content"]);
			$val["post_content"] = $this->getArgs["sub"] == 0 ? $post[0] : mgSubStr($post[0],0,$this->getArgs["sub"]);
		}
		$val["post_alt"] = $num%2;
		$val["post_tags"] = $val["post_tags"] ? explode(",",$val["post_tags"]) : array();
		$val["post_time"] = 
		mgDate($this->getArgs["time_format"],$this->stack['static_var']['time_zone'] - $val["post_gmt"],$val["post_time"]);
		
		return $val;
	}

	public function runModule($args)
	{
		$require = array('sub' 	  			=> $this->stack['static_var']['post_sub'],	//摘要字数
						 'limit'  			=> $this->stack['static_var']['post_page_num'],	//每页篇数
						 'type'				=> 0,
						 'striptags'		=> 0,
						 'content'			=> 0,
						 'time_format'		=> $this->stack['static_var']['post_date_format'],
						);
		$this->getArgs = $this->initArgs($args,$require);
		$postModel = $this->loadModel('posts');

		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$offset = ($page - 1)*$this->getArgs['limit'];

		switch ($this->getArgs['type'])
		{
			case 0:
				
				return $postModel->listAllEntriesIncludeHidden($this->getArgs['limit'],$offset,array('function' => array($this,'prasePost')));
				break;
			case 1:
				return $postModel->listAllEntries($this->getArgs['limit'],$offset,array('function' => array($this,'prasePost')));
				break;
			case 2:
			default:
				return $postModel->listAllPosts($this->getArgs['limit'],$offset,array('function' => array($this,'prasePost')));
				break;
		}
	}
}
?>
