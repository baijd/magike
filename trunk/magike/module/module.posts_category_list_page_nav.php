<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.posts_category_list_page_nav.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class PostsCategoryListPageNav extends MagikeModule
{
	private $getArgs;

	public function runModule($args)
	{
		$require = array('limit'  			=> $this->stack['static_var']['post_page_num']	//每页篇数
						);
		
		$this->getArgs = $this->initArgs($args,$require);
		$postModel = $this->loadModel('posts');
		$total = $postModel->countPostsByCategory($_GET['category_name']);
		
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$result['query'] = $query;
		$result['next'] = $total > $page*$this->getArgs['limit'] ? $page + 1 : 0;
		$result['prev'] = $page > 1 ? $page - 1 : 0;
		$result['total'] = $total%$this->getArgs['limit'] > 0 ? intval($total/$this->getArgs['limit']) + 1
		: intval($total/$this->getArgs['limit']);
		
		return $result;
	}
}
?>
