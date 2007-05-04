<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.comments_list_all_page_nav.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class CommentsListAllPageNav extends MagikeModule
{
	private $getArgs;

	public function runModule($args)
	{
		$require = array('limit'  => 20);
		$this->getArgs = $this->initArgs($args,$require);
		$commentsModel = $this->loadModel('comments');
		
		$total = $commentsModel->getAllCommentsNum();
		$page = isset($_GET['comment_page']) ? $_GET['comment_page'] : 1;
		$result['next'] = $total > $page*$this->getArgs['limit'] ? $page + 1 : 0;
		$result['prev'] = $page > 1 ? $page - 1 : 0;
		$result['total'] = $total%$this->getArgs['limit'] > 0 ? intval($total/$this->getArgs['limit']) + 1
		: intval($total/$this->getArgs['limit']);
		
		return $result;
	}
}
?>
