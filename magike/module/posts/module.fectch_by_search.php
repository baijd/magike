<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.fectch_by_search.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class FectchBySearch extends Posts
{
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
		$this->getPage($this->getArgs['limit']);
		
		$keywords = array();
		$query = 'keywords='.$_GET['keywords'];
		$keywords['post_title'] = $_GET['keywords'];
		$keywords['post_tags'] = $_GET['keywords'];
		$keywords['post_content'] = $_GET['keywords'];
		
		if(isset($this->stack['static_var']['admin_title']))
		{
			$this->stack['admin_menu_list']['children'][1]['menu_name'] = '搜索关键字 "'.$_GET['keywords'].'"';
			$this->stack['admin_menu_list']['children'][1]['path_name'] = '/admin/posts/all/search/?'.$query;
			$this->stack['static_var']['admin_title'] = '搜索关键字 "'.$_GET['keywords'].'"';
		}
		else
		{
			$this->stack['static_var']['blog_title'] = '搜索关键字 "'.$_GET['keywords'].'"'.' &raquo; '.$this->stack['static_var']['blog_name'];
		}
		
		return $this->model->fectchPostsByKeywords($keywords,$this->getArgs['limit'],$this->offset,array('function' => array($this,'prasePost')));

	}
}
?>
