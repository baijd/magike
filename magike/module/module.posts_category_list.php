<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.posts_category_list.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class PostsCategoryList extends MagikeModule
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
		$val["post_tags"] = explode(",",$val["post_tags"]);
		$val["post_time"] = 
		mgDate($this->getArgs["time_format"],$this->stack['static_var']['time_zone'] - $val["post_gmt"],$val["post_time"]);

		return $val;
	}

	public function runModule($args)
	{
		$this->requireGet('keywords');
		$require = array('sub' 	  			=> $this->stack['static_var']['post_sub'],	//摘要字数
						 'limit'  			=> $this->stack['static_var']['post_page_num'],	//每页篇数
						 'striptags'		=> 0,
						 'content'			=> 0,
						 'time_format'		=> $this->stack['static_var']['post_date_format'],
						);
		$this->getArgs = $this->initArgs($args,$require);
		$postModel = $this->loadModel('posts');
		
		return $postModel->fectchPostsByCategory($_GET['category_name'],$this->getArgs['limit'],$offset,array('function' => array($this,'prasePost')));
	}
}
?>
