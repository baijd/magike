<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.posts_list.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class PostsList extends MagikeModule
{
	private $result;
	
	function __construct()
	{
		parent::__construct(array('public' => array('database')));
	}

	private function prasePageInformation()
	{
		$pageInfo = array();
		$pageInfo['sum'] = $this->database->count(array('table' => 'table.posts'));
		$pageInfo['limit'] = isset($this->stack['static_var']['admin_posts_limit']) ? $this->stack['static_var']['admin_posts_limit'] : 20;
		return $pageInfo;
	}

	public function prasePage()
	{
		//初始化查询语句
		$args = array('table'	=> 'table.posts JOIN table.categories ON table.posts.category_id = table.categories.id',
					  'groupby' => 'table.posts.id',
					  'fields'	=> '*,table.posts.id AS post_id',
					  'orderby' => 'table.posts.post_time DESC',
					  );

		$args['offset'] = $_GET['page'] - 1;
		$this->result = $this->database->fectch($args);
	}

	public function runModule()
	{
		if(!$this->onGet('page','prasePage'))
		{
			$this->result = $this->prasePageInformation();
		}
		
		return $this->result;
	}
}
?>
