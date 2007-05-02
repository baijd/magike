<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.posts_list_page_nav.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class PostsListPageNav extends MagikeModule
{
	private $getArgs;

	public function runModule($args)
	{
		$require = array('limit'  			=> $this->stack['static_var']['post_page_num'],	//每页篇数
						 'type'				=> 0
						);
		$this->getArgs = $this->initArgs($args,$require);
		$postModel = $this->loadModel('posts');

		switch ($this->getArgs['type'])
		{
			case 0:
				$total = $postModel->countAllEntriesIncludeHidden();
				break;
			case 1:
				$total = $postModel->countAllEntries();
				break;
			case 2:
			default:
				$total = $postModel->countAllPosts();
				break;
		}
		
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$result['next'] = $total > $page*$this->getArgs['limit'] ? $page + 1 : 0;
		$result['prev'] = $page > 1 ? $page - 1 : 0;
		$result['total'] = $total%$this->getArgs['limit'] > 0 ? intval($total/$this->getArgs['limit']) + 1
		: intval($total/$this->getArgs['limit']);
		
		return $result;
	}
}
?>
