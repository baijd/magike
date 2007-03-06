<?php
/**********************************
 * Created on: 2007-3-6
 * File Name : module.post.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class Post extends MagikeModule
{
	function __construct()
	{
		parent::__construct(array('public' => array('database')));
	}
	
	public function runModule()
	{
		$args = array('fields'=> '*,table.posts.id AS post_id',
					  'table' => 'table.posts JOIN table.categories ON table.posts.category_id = table.categories.id',
					  'groupby' => 'table.posts.id',
					  );

		if(isset($_GET['post_id']))
		{
			$args['where']['template'] = 'table.posts.id = ?';
			$args['where']['value'] = array($_GET['post_id']);
		}
		if(isset($_GET['post_name']))
		{
			$args['where']['template'] = 'table.posts.post_name = ?';
			$args['where']['value'] = array($_GET['post_name']);
		}
		
		return $this->database->fectch($args,array('function' => array($this,'prasePost')));
	}
}
?>
