<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.comments.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class Comments extends PageNavigator
{
	public function runModule($args)
	{
		$require = array('limit'  => 20);
		$getArgs = $this->initArgs($args,$require);
		$commentsModel = $this->loadModel('comments');
		$total = $commentsModel->getAllCommentsNum();
		return $this->makeClassicNavigator($getArgs['limit'],$total,'admin/comments/all/?comment_page=','comment_page');
	}
}
?>
