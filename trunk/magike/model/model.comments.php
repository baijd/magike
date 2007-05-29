<?php
/**********************************
 * Created on: 2006-12-3
 * File Name : model.comments.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class CommentsModel extends MagikeModel
{	
	public function getAllComments($limit,$offset,$sort,$orderby,$func = NULL,$field = NULL,$value = NULL)
	{
		$args = array('fields'=> '*,table.comments.id AS comment_id',
					  'table' => 'table.comments LEFT JOIN table.posts ON table.comments.post_id = table.posts.id',
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
		
		return $this->fectch($args,$func);
	}
	
	public function getAllCommentsNum($field = NULL,$value = NULL)
	{
		$args = array('table' => 'table.comments JOIN table.posts ON table.comments.post_id = table.posts.id');
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
					  'table' => 'table.comments JOIN table.posts ON table.comments.post_id = table.posts.id',
					  'groupby' => 'table.comments.id',
					  'where' => array('template'),
					  'limit' => $limit,
					  'offset' => $offset,
					  'sort'   => $sort,
					  'orderby'=> $orderby);
		
		$args['where'] = array();
		$args['where']['template'] = "comment_publish = 'approved' AND comment_type = 'ping'";
		if(NULL !== $field && NULL !== $value)
		{
			$args['where']['template'] .= " AND {$field} = ?";
			$args['where']['value'] = array($value);
		}
		
		return $this->fectch($args,$func);
	}
	
	public function getAllPublishTrackbacksNum($field = NULL,$value = NULL)
	{
		$args = array('table' => 'table.comments JOIN table.posts ON table.comments.post_id = table.posts.id');
		$args['where'] = array();
		$args['where']['template'] = "comment_publish = 'approved' AND comment_type = 'ping'";
		if(NULL !== $field && NULL !== $value)
		{
			$args['where']['template'] .= " AND {$field} = ?";
			$args['where']['value'] = array($value);
		}
		
		return $this->countTable($args);
	}
	
	public function getAllPublishComments($limit,$offset,$sort,$orderby,$func = NULL,$field = NULL,$value = NULL)
	{
		$args = array('fields'=> '*,table.comments.id AS comment_id',
					  'table' => 'table.comments JOIN table.posts ON table.comments.post_id = table.posts.id',
					  'groupby' => 'table.comments.id',
					  'where' => array('template'),
					  'limit' => $limit,
					  'offset' => $offset,
					  'sort'   => $sort,
					  'orderby'=> $orderby);
		
		$args['where'] = array();
		$args['where']['template'] = "comment_publish = 'approved' AND comment_type = 'comment'";
		if(NULL !== $field && NULL !== $value)
		{
			$args['where']['template'] .= " AND {$field} = ?";
			$args['where']['value'] = array($value);
		}
		
		return $this->fectch($args,$func);
	}
	
	public function getAllPublishCommentsNum($field = NULL,$value = NULL)
	{
		$args = array('table' => 'table.comments JOIN table.posts ON table.comments.post_id = table.posts.id');
		$args['where'] = array();
		$args['where']['template'] = "comment_publish = 'approved' AND comment_type = 'comment'";
		if(NULL !== $field && NULL !== $value)
		{
			$args['where']['template'] .= " AND {$field} = ?";
			$args['where']['value'] = array($value);
		}
		
		return $this->countTable($args);
	}
	
	public function getAllPublishCommentsTrackbacks($limit,$offset,$sort,$orderby,$func = NULL,$field = NULL,$value = NULL)
	{
		$args = array('fields'=> '*,table.comments.id AS comment_id',
					  'table' => 'table.comments JOIN table.posts ON table.comments.post_id = table.posts.id',
					  'groupby' => 'table.comments.id',
					  'where' => array('template'),
					  'limit' => $limit,
					  'offset' => $offset,
					  'sort'   => $sort,
					  'orderby'=> $orderby);
		
		$args['where'] = array();
		$args['where']['template'] = "comment_publish = 'approved'";
		if(NULL !== $field && NULL !== $value)
		{
			$args['where']['template'] .= " AND {$field} = ?";
			$args['where']['value'] = array($value);
		}
		
		return $this->fectch($args,$func);
	}
	
	public function getAllPublishCommentsTrackbacksNum($field = NULL,$value = NULL)
	{
		$args = array('table' => 'table.comments LEFT JOIN table.posts ON table.comments.post_id = table.posts.id');
		$args['where'] = array();
		$args['where']['template'] = "comment_publish = 'approved'";
		if(NULL !== $field && NULL !== $value)
		{
			$args['where']['template'] .= " AND {$field} = ?";
			$args['where']['value'] = array($value);
		}
		
		return $this->countTable($args);
	}
}
?>
