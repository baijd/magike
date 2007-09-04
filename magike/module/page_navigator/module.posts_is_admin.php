<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.posts_is_admin.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class PostsIsAdmin extends PageNavigator
{
	public function runModule($args)
	{
		$require = array('limit'  => $this->stack['static_var']['post_page_num']);
		$getArgs = $this->initArgs($args,$require);
		$postModel = $this->loadModel('posts');
		
		if($this->stack['access']['user_group'] <= $this->stack['static_var']['group']['editor'])
		{
			$total = $postModel->countAllEntries();
		}
		else
		{
			$total = $postModel->countAllPostsByAuthor($this->stack['access']['user_id']);
		}
		
		return $this->makeClassicNavigator($getArgs['limit'],$total,'admin/posts/all/?page=');
	}
}
?>
