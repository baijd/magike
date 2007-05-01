<?php
/**********************************
 * Created on: 2006-12-3
 * File Name : model.posts.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class PostsModel extends MagikeModel
{
	public function fectchPostById($id,$func = NULL)
	{
		$args = array('fields'=> '*,table.posts.id AS post_id',
			  'table' => 'table.posts JOIN table.categories ON table.posts.category_id = table.categories.id',
			  'groupby' => 'table.posts.id',
			  );
		$args['where']['template'] = 'table.posts.id = ?';
		$args['where']['value'] = array($id);
		return $this->fectchOne($args,$func);
	}
	
	public function fectchPostByName($name,$func = NULL)
	{
		$args = array('fields'=> '*,table.posts.id AS post_id',
			  'table' => 'table.posts JOIN table.categories ON table.posts.category_id = table.categories.id',
			  'groupby' => 'table.posts.id',
			  );
		$args['where']['template'] = 'table.posts.post_name = ?';
		$args['where']['value'] = array($name);
		return $this->fectchOne($args,$func);
	}
}
?>
