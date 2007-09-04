<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.posts_fetch_by_search.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class PostsFetchBySearch extends PageNavigator
{
	private $getArgs;
	private $result;
	
	public function outputPageNav()
	{
		$postModel = $this->loadModel('posts');
		$keywords = array();

		$keywords['post_title'] = $_GET['keywords'];
		$keywords['post_tags'] = $_GET['keywords'];
		$keywords['post_content'] = $_GET['keywords'];
		
		$total = $postModel->countPostsByKeywords($keywords,$this->getArgs['supper']);
		$this->result = $this->makeClassicNavigator($this->getArgs['limit'],$total,'search/?keywords='.$_GET['keywords'].'&page=');
	}
	public function runModule($args)
	{
		$require = array('limit'  => $this->stack['static_var']['post_page_num']	,'supper' => 0);
		$this->getArgs = $this->initArgs($args,$require);
		$this->onGet('keywords','outputPageNav');
		return $this->result;
	}
}
?>
