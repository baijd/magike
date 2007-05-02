<?php
/**********************************
 * Created on: 2006-12-3
 * File Name : model.posts.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class PostsModel extends MagikeModel
{
	private function fixPostWhere($limit = 0,$offset = 0,$where = NULL)
	{
		$args = array('table'	=> 'table.posts LEFT JOIN table.categories ON table.posts.category_id = table.categories.id',
					  'groupby' => 'table.posts.id',
					  'fields'	=> '*,table.posts.id AS post_id',
					  'orderby' => 'table.posts.post_time',
					  'limit'	=> $limit,
					  'offset'	=> $offset,
					  'sort'	=> 'DESC'
			  		);
		if($where)
		{
			$args['where'] = $where;
		}
		
		return $args;
	}
	
	public function fectchPostById($id,$func = NULL)
	{
		$args = array('fields'=> '*,table.posts.id AS post_id',
			  'table' => 'table.posts LEFT JOIN table.categories ON table.posts.category_id = table.categories.id',
			  'groupby' => 'table.posts.id',
			  );
		$args['where']['template'] = 'table.posts.id = ?';
		$args['where']['value'] = array($id);
		return $this->fectchOne($args,$func);
	}
	
	public function fectchPostByName($name,$func = NULL)
	{
		$args = array('fields'=> '*,table.posts.id AS post_id',
			  'table' => 'table.posts LEFT JOIN table.categories ON table.posts.category_id = table.categories.id',
			  'groupby' => 'table.posts.id',
			  );
		$args['where']['template'] = 'table.posts.post_name = ?';
		$args['where']['value'] = array($name);
		return $this->fectchOne($args,$func);
	}
	
	public function listAllPosts($limit,$offset,$func = NULL)
	{
		return $this->fectch($this->fixPostWhere($limit,$offset),$func);
	}
	
	public function countAllPosts()
	{
		$args = $this->fixPostWhere();
		unset($args['groupby']);
		return $this->countTable($args);
	}
	
	public function listAllEntries($limit,$offset,$func = NULL)
	{
		$where = array('template'	=> 'post_is_draft = 0 AND post_is_page = 0 AND post_is_hidden = 0');
		return $this->fectch($this->fixPostWhere($limit,$offset,$where),$func);
	}
	
	public function countAllEntries()
	{
		$where = array('template'	=> 'post_is_draft = 0 AND post_is_page = 0 AND post_is_hidden = 0');
		$args = $this->fixPostWhere(0,0,$where);
		unset($args['groupby']);
		return $this->countTable($args);
	}
	
	public function listAllEntriesIncludeHidden($limit,$offset,$func = NULL)
	{
		$where = array('template'	=> 'post_is_draft = 0 AND post_is_page = 0');
		return $this->fectch($this->fixPostWhere($limit,$offset,$where),$func);
	}
	
	public function countAllEntriesIncludeHidden()
	{
		$where = array('template'	=> 'post_is_draft = 0 AND post_is_page = 0');
		$args = $this->fixPostWhere(0,0,$where);
		unset($args['groupby']);
		return $this->countTable($args);
	}
}
?>
