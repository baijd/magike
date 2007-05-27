<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.posts_search_list_page_nav.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class PostsSearchListPageNav extends MagikeModule
{
	private $getArgs;

	public function runModule($args)
	{
		$require = array('limit'  			=> $this->stack['static_var']['post_page_num']	//每页篇数
						);
		$this->getArgs = $this->initArgs($args,$require);
		$postModel = $this->loadModel('posts');
		$keywords = array();
		$query = 'keywords='.$_GET['keywords'];
		if(isset($_GET['title']) && $_GET['title'] == 1)
		{
			$keywords['post_title'] = $_GET['keywords'];
			$query .= '&title=1';
		}
		if(isset($_GET['tags']) && $_GET['tags'] == 1)
		{
			$keywords['post_tags'] = $_GET['keywords'];
			$query .= '&tags=1';
		}
		if(isset($_GET['content']) && $_GET['content'] == 1)
		{
			$keywords['post_content'] = $_GET['keywords'];
			$query .= '&content=1';
		}
		if(isset($_GET['category']) && $_GET['category'] == 1)
		{
			$keywords['category_name'] = $_GET['keywords'];
			$query .= '&category=1';
		}
		else
		{
			$keywords['post_title'] = $_GET['keywords'];
			$query .= '&title=1';
		}
		
		$total = $postModel->countPostsByKeywords($keywords);
		
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
