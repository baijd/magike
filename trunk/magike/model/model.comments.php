<?php
/**********************************
 * Created on: 2006-12-3
 * File Name : model.comments.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class CommentsModel extends MagikeModel
{	
	function __construct()
	{
		parent::__construct('table.comments','id');
	}
	
	public function getAllComments($limit,$offset,$sort,$orderby,$func = NULL,$field = NULL,$value = NULL)
	{
		$args = array('fields'=> '*,table.comments.id AS comment_id',
					  'table' => '(table.comments JOIN table.posts ON table.comments.post_id = table.posts.id) JOIN table.categories ON table.posts.category_id = table.categories.id',
					  'groupby' => 'table.comments.id',
					  'limit' => $limit,
					  'offset' => $offset,
					  'sort'   => $sort,
					  'orderby'=> $orderby);
		if(NULL !== $field && NULL !== $value)
		{
			$args['where'] = array();
			$args['where']['template'] = "{$field} = ?";
			$args['where']['value'] = array($value);
		}
		
		return $this->fetch($args,$func);
	}
	
	public function getAllCommentsNum($field = NULL,$value = NULL)
	{
		$args = array('table' => '(table.comments JOIN table.posts ON table.comments.post_id = table.posts.id) JOIN table.categories ON table.posts.category_id = table.categories.id');
		if(NULL !== $field && NULL !== $value)
		{
			$args['where'] = array();
			$args['where']['template'] = "{$field} = ?";
			$args['where']['value'] = array($value);
		}
		
		return $this->countTable($args);
	}
	
	public function getAllPublishTrackbacks($limit,$offset,$sort,$orderby,$func = NULL,$field = NULL,$value = NULL)
	{
		$args = array('fields'=> '*,table.comments.id AS comment_id',
					  'table' => '(table.comments JOIN table.posts ON table.comments.post_id = table.posts.id) JOIN table.categories ON table.posts.category_id = table.categories.id',
					  'groupby' => 'table.comments.id',
					  'limit' => $limit,
					  'offset' => $offset,
					  'sort'   => $sort,
					  'orderby'=> $orderby);
		
		$args['where'] = array();
		$args['where']['template'] = "comment_publish = 'approved' AND comment_type = 'trackback' AND post_is_hidden = 0";
		if(NULL !== $field && NULL !== $value)
		{
			$args['where']['template'] = "{$field} = ? AND ".$args['where']['template'];
			$args['where']['value'] = array($value);
		}
		
		return $this->fetch($args,$func);
	}
	
	public function getAllPublishTrackbacksNum($field = NULL,$value = NULL)
	{
		$args = array('table' => '(table.comments JOIN table.posts ON table.comments.post_id = table.posts.id) JOIN table.categories ON table.posts.category_id = table.categories.id');
		$args['where'] = array();
		$args['where']['template'] = "comment_publish = 'approved' AND comment_type = 'trackback' AND post_is_hidden = 0";
		if(NULL !== $field && NULL !== $value)
		{
			$args['where']['template'] = "{$field} = ? AND ".$args['where']['template'];
			$args['where']['value'] = array($value);
		}
		
		return $this->countTable($args);
	}
	
	public function getAllPublishComments($limit,$offset,$sort,$orderby,$func = NULL,$field = NULL,$value = NULL)
	{
		$args = array('fields'=> '*,table.comments.id AS comment_id',
					  'table' => '(table.comments JOIN table.posts ON table.comments.post_id = table.posts.id) JOIN table.categories ON table.posts.category_id = table.categories.id',
					  'groupby' => 'table.comments.id',
					  'limit' => $limit,
					  'offset' => $offset,
					  'sort'   => $sort,
					  'orderby'=> $orderby);
		
		$args['where'] = array();
		$args['where']['template'] = "comment_publish = 'approved' AND comment_type = 'comment' AND post_is_hidden = 0";
		if(NULL !== $field && NULL !== $value)
		{
			$args['where']['template'] = "{$field} = ? AND ".$args['where']['template'];
			$args['where']['value'] = array($value);
		}
		
		return $this->fetch($args,$func);
	}
	
	public function getAllPublishCommentsNum($field = NULL,$value = NULL)
	{
		$args = array('table' => '(table.comments JOIN table.posts ON table.comments.post_id = table.posts.id) JOIN table.categories ON table.posts.category_id = table.categories.id');
		$args['where'] = array();
		$args['where']['template'] = "comment_publish = 'approved' AND comment_type = 'comment' AND post_is_hidden = 0";
		if(NULL !== $field && NULL !== $value)
		{
			$args['where']['template'] = "{$field} = ? AND ".$args['where']['template'];
			$args['where']['value'] = array($value);
		}
		
		return $this->countTable($args);
	}
	
	public function getAllPublishCommentsTrackbacksPingbacks($limit,$offset,$sort,$orderby,$func = NULL,$field = NULL,$value = NULL)
	{
		$args = array('fields'=> '*,table.comments.id AS comment_id',
					  'table' => '(table.comments JOIN table.posts ON table.comments.post_id = table.posts.id) JOIN table.categories ON table.posts.category_id = table.categories.id',
					  'groupby' => 'table.comments.id',
					  'limit' => $limit,
					  'offset' => $offset,
					  'sort'   => $sort,
					  'orderby'=> $orderby);
		
		$args['where'] = array();
		$args['where']['template'] = "comment_publish = 'approved' AND post_is_hidden = 0";
		if(NULL !== $field && NULL !== $value)
		{
			$args['where']['template'] = "{$field} = ? AND ".$args['where']['template'];
			$args['where']['value'] = array($value);
		}
		
		return $this->fetch($args,$func);
	}
	
	public function getAllPublishCommentsTrackbacksPingbacksNum($field = NULL,$value = NULL)
	{
		$args = array('table' => '(table.comments JOIN table.posts ON table.comments.post_id = table.posts.id) JOIN table.categories ON table.posts.category_id = table.categories.id');
		$args['where'] = array();
		$args['where']['template'] = "comment_publish = 'approved' AND post_is_hidden = 0";
		if(NULL !== $field && NULL !== $value)
		{
			$args['where']['template'] = "{$field} = ? AND ".$args['where']['template'];
			$args['where']['value'] = array($value);
		}
		
		return $this->countTable($args);
	}
}
?>
