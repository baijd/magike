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

	public function runModule()
	{
		$args = array('table'	=> 'table.posts JOIN table.categories ON table.posts.category_id = table.categories.id',
					  'groupby' => 'table.posts.id',
					  'fields'	=> '*,table.posts.id AS post_id',
					  'orderby' => 'table.posts.post_time DESC',
					  );

		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$args['offset'] = $page - 1;
		return $this->database->fectch($args);
	}
}
?>
