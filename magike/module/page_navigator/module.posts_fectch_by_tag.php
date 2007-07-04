<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.posts_fectch_by_tag.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class PostsFectchByTag extends PageNavigator
{
	private $getArgs;
	private $result;
	
	public function outputPageNav()
	{
		$postModel = $this->loadModel('posts');
		$total = $postModel->countPostsByTag($_GET['tag_name']);
		$this->result = $this->makeClassicNavigator($this->getArgs['limit'],$total,'tags/'.$_GET['tag_name'].'/');
		$this->stack['static_var']['blog_title'] = $_GET['tag_name'].' &raquo; 标签 &raquo; '.$this->stack['static_var']['blog_name'];
	}
	
	public function runModule($args)
	{
		$this->result = array();
		$require = array('limit'  => $this->stack['static_var']['post_page_num']);
		$this->getArgs = $this->initArgs($args,$require);
		$this->onGet('tag_name','outputPageNav');
		return $this->result;
	}
}
?>
