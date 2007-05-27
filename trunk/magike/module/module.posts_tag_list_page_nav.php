<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.posts_tag_list_page_nav.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class PostsTagListPageNav extends MagikeModule
{
	private $getArgs;
	private $result;
	
	public function outputPageNav()
	{
		$postModel = $this->loadModel('posts');
		$total = $postModel->countPostsByTag($_GET['tag_name']);
		
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$this->result['query'] = 'tags/'.$_GET['tag_name'];
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

		$this->onGet('tag_name','outputPageNav');
		return $this->result;
	}
}
?>
