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
	private $getArgs;
	
	function __construct()
	{
		parent::__construct();
		$this->post = $this->loadModel('posts');
	}
	
	public function prasePost($val)
	{
		if(isset($this->stack['static_var']['blog_name']))
		{
			$this->stack['static_var']['blog_title'] = $val['post_title'].' &raquo; '.$this->stack['static_var']['blog_name'];
		}
		if(isset($this->stack['static_var']['keywords']) && $val['post_tags'])
		{
			$this->stack['static_var']['keywords'] = $this->stack['static_var']['keywords'].','.$val['post_tags'];
		}
		
		$val["post_utc"] = $this->stack['static_var']['time_zone']+$val["post_time"];
		$val["post_time"] = 
		date($this->getArgs['time_format'],$val["post_utc"]);
		$val["post_year"] = date("Y",$val["post_utc"]);
		$val["post_month"] = date("n",$val["post_utc"]);
		$val["post_day"] = date("j",$val["post_utc"]);
		$val["post_tags"] = $val["post_tags"] ? explode(",",$val["post_tags"]) : array();
		
		if($val['post_is_hidden'])
		{
			if((isset($_COOKIE['post_password']) && $_COOKIE['post_password'] == $val['post_password'])
			|| $this->stack['access']['user_id'] == $val['user_id'])
			{
				$val['post_access'] = true;
			}
			else
			{
				$val['post_access'] = false;
			}
		}
		else
		{
			$val['post_access'] = true;
		}
		
		if($val['post_is_page'])
		{
			$val["permalink"] = $this->stack['static_var']['index'].vsprintf($this->stack['permalink']['pages']['path'],array_intersect_key($val,$this->stack['permalink']['pages']['value']));
		}
		else
		{
			$val["permalink"] = $this->stack['static_var']['index'].vsprintf($this->stack['permalink']['archives']['path'],array_intersect_key($val,$this->stack['permalink']['archives']['value']));
		}
		
		$val["category_permalink"] = $this->stack['static_var']['index'].vsprintf($this->stack['permalink']['category']['path'],array_intersect_key($val,$this->stack['permalink']['category']['value']));
		$val["trackback_permalink"] = $this->stack['static_var']['index'].vsprintf($this->stack['permalink']['trackbacks']['path'],array_intersect_key($val,$this->stack['permalink']['trackbacks']['value']));
		$val["rss_permalink"] = $this->stack['static_var']['index'].vsprintf($this->stack['permalink']['archives_rss']['path'],array_intersect_key($val,$this->stack['permalink']['archives_rss']['value']));
		$val["post_comment_url"] = $this->stack['static_var']['index'].vsprintf($this->stack['permalink']['post_comment']['path'],array_intersect_key($val,$this->stack['permalink']['post_comment']['value']));
		$this->stack['static_var']['rss_permalink'] = $val["rss_permalink"];
		return $val;
	}
	
	public function getAccess()
	{
		setcookie('post_password',$_POST['post_password'],0,'/');
		$_COOKIE['post_password'] = $_POST['post_password'];
		@reset($_COOKIE);
	}
	
	public function runModule($args)
	{
		$require = array('time_format'=> $this->stack['static_var']['post_date_format']);	//日期输出格式
		$this->getArgs = $this->initArgs($args,$require);
		
		$this->onPost('post_password','getAccess');
		if(isset($_GET['post_id']))
		{
			return $this->post->fetchPostById($_GET['post_id'],array('function' => array($this,'prasePost')));
		}
		else if(isset($_GET['post_name']))
		{
			return $this->post->fetchPostByName($_GET['post_name'],array('function' => array($this,'prasePost')));
		}
		else
		{
			return array();
		}
	}
}
?>
