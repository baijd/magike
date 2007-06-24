<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.posts.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class Posts extends PageNavigator
{
	public function runModule($args)
	{
		$require = array('limit'  => $this->stack['static_var']['post_page_num']);
		$getArgs = $this->initArgs($args,$require);
		$postModel = $this->loadModel('posts');
		$total = $postModel->countAllPosts();
		return $this->makeClassicNavigator($getArgs['limit'],$total);
	}
}
?>
