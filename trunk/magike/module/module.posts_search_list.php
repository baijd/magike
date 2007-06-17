<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.posts_search_list.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class PostsSearchList extends MagikeModule
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
		date($this->stack['static_var']['post_date_format'],$this->stack['static_var']['time_zone']+$val["post_time"]);

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

		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$offset = ($page - 1)*$this->getArgs['limit'];
		$keywords = array();
		$query = 'keywords='.$_GET['keywords'];
		if(isset($_GET['title']) && $_GET['title'] == 1)
		{
			$keywords['post_title'] = $_GET['keywords'];
			$query .= '&title=1';
		}
		if(isset($_GET['tags']) && $_GET['tags'] == 1)
		{
			$keywords['post_tags'] = $_GET['keywords'];
			$query .= '&tags=1';
		}
		if(isset($_GET['content']) && $_GET['content'] == 1)
		{
			$keywords['post_content'] = $_GET['keywords'];
			$query .= '&content=1';
		}
		if(isset($_GET['category']) && $_GET['category'] == 1)
		{
			$keywords['category_name'] = $_GET['keywords'];
			$query .= '&category=1';
		}
		else
		{
			$keywords['post_title'] = $_GET['keywords'];
			$query .= '&title=1';
		}
		
		$this->stack['admin_menu_list']['children'][1]['menu_name'] = '搜索关键字 "'.$_GET['keywords'].'"';
		$this->stack['admin_menu_list']['children'][1]['path_name'] = '/admin/posts/all/search/?'.$query;
		$this->stack['static_var']['admin_title'] = '搜索关键字 "'.$_GET['keywords'].'"';
		
		return $postModel->fectchPostsByKeywords($keywords,$this->getArgs['limit'],$offset,array('function' => array($this,'prasePost')));

	}
}
?>
