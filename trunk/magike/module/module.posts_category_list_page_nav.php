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
	private $result;
	
	public function outputPageNav()
	{
		$postModel = $this->loadModel('posts');
		$total = $postModel->countPostsByCategory($_GET['category_postname']);
		
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$this->result['query'] = 'category/'.$_GET['category_postname'];
		$this->result['next'] = $total > $page*$this->getArgs['limit'] ? $page + 1 : 0;
		$this->result['prev'] = $page > 1 ? $page - 1 : 0;
		$this->result['total'] = $total%$this->getArgs['limit'] > 0 ? intval($total/$this->getArgs['limit']) + 1
		: intval($total/$this->getArgs['limit']);
	}

	public function runModule($args)
	{
		$this->result = array();
		$require = array('limit'  			=> $this->stack['static_var']['post_page_num']);
		
		$this->getArgs = $this->initArgs($args,$require);

		$this->onGet('category_postname','outputPageNav');
		return $this->result;
	}
}
?>
