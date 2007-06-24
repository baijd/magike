<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.posts_fectch_by_search.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class PostsFectchBySearch extends PageNavigator
{
	public function runModule($args)
	{
		$require = array('limit'  => $this->stack['static_var']['post_page_num']	);
		$getArgs = $this->initArgs($args,$require);
		$postModel = $this->loadModel('posts');
		$keywords = array();

		$keywords['post_title'] = $_GET['keywords'];
		$keywords['post_tags'] = $_GET['keywords'];
		$keywords['post_content'] = $_GET['keywords'];
		
		$total = $postModel->countPostsByKeywords($keywords);
		return $this->makeClassicNavigator($getArgs['limit'],$total,'keywords='.$_GET['keywords']);
	}
}
?>
