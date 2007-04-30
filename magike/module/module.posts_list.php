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
	
	function __construct()
	{
		parent::__construct(array('public' => array('database')));
	}
	
	public function prasePost($val,$num)
	{
		$val["post_content"] = $this->getArgs["striptags"] ? mgStripTags($val["post_content"]) : $val["post_content"];
		$post = explode("<!--more-->",$val["post_content"]);
		$val["post_content"] = $this->getArgs["sub"] == 0 ? $post[0] : mgSubStr($post[0],0,$this->getArgs["sub"]);
		$val["post_alt"] = $num%2;
		$val["post_time"] = 
		mgDate($this->stack['static_var']['post_date_format'],$this->stack['static_var']['time_zone'] - $val["post_gmt"],$val["post_time"]);

		return $val;
	}

	public function runModule($args)
	{
		$require = array('sub' 	  			=> $this->stack['static_var']['post_sub'],	//摘要字数
						 'limit'  			=> $this->stack['static_var']['post_page_num'],	//每页篇数
						 'sort'				=> 'DESC',	//排序方式
						 'type'				=> 0,
						 'striptags'		=> 0
						);
		$this->getArgs = $this->initArgs($args,$require);
		$args = array('table'	=> 'table.posts JOIN table.categories ON table.posts.category_id = table.categories.id',
					  'groupby' => 'table.posts.id',
					  'fields'	=> '*,table.posts.id AS post_id',
					  'orderby' => 'table.posts.post_time',
					  'limit'	=> $this->getArgs['limit'],
					  'sort'	=> 'DESC'
			  );

		switch ($this->getArgs['type'])
		{
			case 0:
				$args['where'] = array('where'	=> array(
										'template'	=> 'post_is_draft = 0 AND post_is_hidden = 0 AND post_is_page = 0'
								 ));
				break;
			case 1:
				$args['where'] = array('where'	=> array(
										'template'	=> 'post_is_draft = 0 AND post_is_page = 0'
								 ));
				break;
			case 2:
				break;
			default:
				break;
		}

		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$args['offset'] = ($page - 1)*$this->getArgs['limit'];
		return $this->database->fectch($args,array('function' => array($this,'prasePost')));
	}
}
?>
