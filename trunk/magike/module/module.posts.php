<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.posts.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class Posts extends MagikeModule
{
	protected $getArgs;
	protected $page;
	protected $offset;
	protected $model;

	function __construct($loadModel = true)
	{
		parent::__construct();
		
		if($loadModel)
		{
			$this->model = $this->loadModel('posts');
		}
	}
	
	protected function getPage($limit,$page = 'page')
	{
		$this->page = isset($_GET[$page]) ? $_GET[$page] : 1;
		$this->offset = ($this->page - 1)*$limit;
	}
	
	public function prasePost($val,$num,$last)
	{
		$val["post_content"] = $this->getArgs["striptags"] ? mgStripTags($val["post_content"]) : $val["post_content"];
		if(!$this->getArgs["content"])
		{
			$post = explode("<!--more-->",$val["post_content"]);
			$val["post_content"] = $this->getArgs["sub"] == 0 ? $post[0] : mgSubStr($post[0],0,$this->getArgs["sub"]);
		}
		$val["post_alt"] = $num%2;
		$val["post_tags"] = $val["post_tags"] ? explode(",",$val["post_tags"]) : array();
		$val["post_utc"] = $this->stack['static_var']['time_zone']+$val["post_time"];
		$val["post_time"] = 
		date($this->getArgs["time_format"],$val["post_utc"]);
		$val["post_year"] = date("Y",$val["post_utc"]);
		$val["post_month"] = date("n",$val["post_utc"]);
		$val["post_day"] = date("j",$val["post_utc"]);
		$val["post_is_last"] = $last;
		
		if($val['post_is_page'])
		{
			$val["permalink"] = $this->stack['static_var']['index'].vsprintf($this->stack['permalink']['pages']['path'],mgArrayIntersectKey($val,$this->stack['permalink']['pages']['value']));
		}
		else
		{
			$val["permalink"] = $this->stack['static_var']['index'].vsprintf($this->stack['permalink']['archives']['path'],mgArrayIntersectKey($val,$this->stack['permalink']['archives']['value']));
		}
		
		$val["category_permalink"] = $this->stack['static_var']['index'].vsprintf($this->stack['permalink']['category']['path'],mgArrayIntersectKey($val,$this->stack['permalink']['category']['value']));
		$val["trackback_permalink"] = $this->stack['static_var']['index'].vsprintf($this->stack['permalink']['trackbacks']['path'],mgArrayIntersectKey($val,$this->stack['permalink']['trackbacks']['value']));
		$val["rss_permalink"] = $this->stack['static_var']['index'].vsprintf($this->stack['permalink']['archives_rss']['path'],mgArrayIntersectKey($val,$this->stack['permalink']['archives_rss']['value']));
		$val["post_comment_url"] = $this->stack['static_var']['index'].vsprintf($this->stack['permalink']['post_comment']['path'],mgArrayIntersectKey($val,$this->stack['permalink']['post_comment']['value']));
		return $val;
	}
	
	public function runModule($args)
	{
		$require = array('sub' 	  	=> $this->stack['static_var']['post_sub'],
					 'limit'  		=> $this->stack['static_var']['post_page_num'],
					 'striptags'	=> 0,
					 'content'		=> 0,
					 'time_format'	=> $this->stack['static_var']['post_date_format'],
					);
		$this->getArgs = $this->initArgs($args,$require);
		$this->getPage($this->getArgs['limit']);
		
		return $this->model->listAllPosts($this->getArgs['limit'],$this->offset,array('function' => array($this,'prasePost')));
	}
}
?>
