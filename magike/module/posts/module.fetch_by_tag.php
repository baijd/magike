<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.fetch_by_tag.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
 class FetchByTag extends Posts
 {
 	private $result;
	
	public function outputPosts()
	{
		$this->getPage($this->getArgs['limit']);
		$this->result = $this->model->fetchPostsByTag($_GET['tag_name'],$this->getArgs['limit'],$this->offset,array('function' => array($this,'prasePost')));
	}
	
	public function runModule($args)
	{
		$this->result = array();
		$require = array(  'sub' 	  	=> $this->stack['static_var']['post_sub'],	//摘要字数
					 'limit'  		=> $this->stack['static_var']['post_page_num'],//每页篇数
					 'striptags'		=> 0,
					 'content'		=> 0,
					 'time_format'	=> $this->stack['static_var']['post_date_format'],
					);
		$this->getArgs = $this->initArgs($args,$require);
		$this->onGet('tag_name','outputPosts');
		return $this->result;
	}
 }
 ?>
