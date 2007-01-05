<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : admin_posts_list_module.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class AdminPostsListModule extends MagikeModule
{
	function __construct()
	{
		parent::__construct(array('database','stack'));
	}
	
	private function prasePage()
	{
		//初始化查询语句
		$args = array('table'	=> 'table.posts JOIN table.categories ON table.posts.category_id = table.categories.id',
					  'groupby' => 'table.posts.id',
					  'fields'	=> '*,table.posts.id AS post_id',
					  'orderby' => 'table.posts.post_time DESC',
					  );

		$args['limit']  = isset($this->stack->data['static']['admin_posts_limit']) ? $this->stack->data['static']['admin_posts_limit'] : 20;
		$args['offset'] = $_GET['page'] - 1;
		header("content-Type: {$this->stack->data['static']['content_type']}; charset={$this->stack->data['static']['charset']}");
		echo json_encode($this->database->fectch($args));
	}

	private function prasePageInformation()
	{
		$pageInfo = array();
		$pageInfo['sum'] = $this->database->count(array('table' => 'table.posts'));
		$pageInfo['sub'] = isset($this->stack->data['static']['admin_posts_limit']) ? $this->stack->data['static']['admin_posts_limit'] : 20;
		header("content-Type: {$this->stack->data['static']['content_type']}; charset={$this->stack->data['static']['charset']}");
		echo json_encode($pageInfo);
	}

	public function runModule()
	{
		if(!$this->onGet('page','prasePage'))
		{
			$this->prasePageInformation();
		}
	}
}
?>
