<?php
/**********************************
 * Created on: 2006-12-3
 * File Name : model.posts.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class PostsModel extends MagikeModel
{
	private function fixPostWhere($limit = false,$offset = false,$where = NULL)
	{
		$args = array('table'	=> 'table.posts LEFT JOIN table.categories ON table.posts.category_id = table.categories.id',
					  'groupby' => 'table.posts.id',
					  'fields'	=> '*,table.posts.id AS post_id',
					  'orderby' => 'table.posts.post_time',
					  'sort'	=> 'DESC'
			  		);
		
		if(false !== $offset)
		{
			$args['offset'] = $offset;
		}
		if(false !== $limit)
		{
			$args['limit'] = $limit;
		}
 	 	 
		if($where)
		{
			$args['where'] = $where;
		}
		
		return $args;
	}
	
	public function fectchPostsByKeywords($keywords,$limit,$offset,$func = NULL)
	{
		$whereTpl = array();
		$value = array();
		$where = array();
		
		foreach($keywords as $key => $val)
		{
			$whereTpl[] = "{$key} LIKE ?";
			$value[] = '%'.$val.'%';
		}
		
		$where['template'] = implode(' OR ',$whereTpl);
		$where['value'] = $value;
		return $this->fectch($this->fixPostWhere($limit,$offset,$where),$func);
	}
	
	public function countPostsByKeywords($keywords)
	{
		$args = $this->fixPostWhere();
		
		$whereTpl = array();
		$value = array();
		foreach($keywords as $key => $val)
		{
			$whereTpl[] = "{$key} LIKE ?";
			$value[] = '%'.$val.'%';
		}
		
		$args['where']['template'] = implode(' OR ',$whereTpl);
		$args['where']['value'] = $value;
		unset($args['groupby']);
		return $this->countTable($args);
	}
	
	public function fectchPostsByTag($tag,$limit,$offset,$func = NULL)
	{
		$args['table'] = '((table.posts JOIN table.post_tag_mapping ON table.posts.id = table.post_tag_mapping.post_id)
		 LEFT JOIN table.tags ON table.post_tag_mapping.tag_id = table.tags.id)';
		$args['where']['template'] = 'table.tags.tag_name = ?';
		$args['where']['value'] = array($tag);
		$args['limit'] = $limit;
		$args['offset'] = $offset;
		$args['groupby'] = 'table.posts.id';
		$args['fields']	= '*,table.posts.id AS post_id';
		$args['orderby'] = 'table.posts.post_time';
		$args['sort']	= 'DESC';
		
		return $this->fectch($args,$func,true);
	}
	
	public function countPostsByTag($tag)
	{
		$args['table'] = '((table.posts JOIN table.post_tag_mapping ON table.posts.id = table.post_tag_mapping.post_id)
		 LEFT JOIN table.tags ON table.post_tag_mapping.tag_id = table.tags.id)';
		
		$args['where']['template'] = 'table.tags.tag_name = ?';
		$args['where']['value'] = array($tag);
		return $this->countTable($args);
	}
	
	public function fectchPostsByCategory($category,$limit,$offset,$func = NULL)
	{
		$where['template'] = 'category_postname = ?';
		$where['value'] = array($category);
		return $this->fectch($this->fixPostWhere($limit,$offset,$where),$func);
	}
	
	public function countPostsByCategory($category)
	{
		$args = $this->fixPostWhere();
		
		$args['where']['template'] = 'category_postname = ?';
		$args['where']['value'] = array($category);
		unset($args['groupby']);
		return $this->countTable($args);
	}
	
	public function fectchPostById($id,$func = NULL,$exception = true)
	{
		$args = array('fields'=> '*,table.posts.id AS post_id',
			  'table' => 'table.posts LEFT JOIN table.categories ON table.posts.category_id = table.categories.id',
			  'groupby' => 'table.posts.id',
			  );
		$args['where']['template'] = 'table.posts.id = ?';
		$args['where']['value'] = array($id);
		return $this->fectchOne($args,$func,$exception);
	}
	
	public function fectchPostByName($name,$func = NULL,$exception = true)
	{
		$args = array('fields'=> '*,table.posts.id AS post_id',
			  'table' => 'table.posts LEFT JOIN table.categories ON table.posts.category_id = table.categories.id',
			  'groupby' => 'table.posts.id',
			  );
		$args['where']['template'] = 'table.posts.post_name = ?';
		$args['where']['value'] = array($name);
		return $this->fectchOne($args,$func,$exception);
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
		$where = array('template'	=> 'post_is_draft = 0 AND post_is_page = 0 AND post_is_hidden = 0 AND post_time < '.(time() - $this->stack['static_var']['server_timezone']));
		return $this->fectch($this->fixPostWhere($limit,$offset,$where),$func);
	}
	
	public function countAllEntries()
	{
		$where = array('template'	=> 'post_is_draft = 0 AND post_is_page = 0 AND post_is_hidden = 0 AND post_time < '.(time() - $this->stack['static_var']['server_timezone']));
		$args = $this->fixPostWhere(false,false,$where);
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
		$args = $this->fixPostWhere(false,false,$where);
		unset($args['groupby']);
		return $this->countTable($args);
	}
	
	public function listAllPages($limit = false,$offset = false,$func = NULL)
	{
		$where = array('template'	=> 'post_is_page = 1 AND post_is_draft = 0 AND post_is_hidden = 0');
		return $this->fectch($this->fixPostWhere($limit,$offset,$where),$func);
	}
	
	public function countAllPages()
	{
		$where = array('template'	=> 'post_is_page = 1 AND post_is_draft = 0 AND post_is_hidden = 0');
		$args = $this->fixPostWhere(false,false,$where);
		unset($args['groupby']);
		return $this->countTable($args);
	}
}
?>
