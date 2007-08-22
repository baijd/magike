<?php
/**********************************
 * Created on: 2006-12-3
 * File Name : model.tags.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class TagsModel extends MagikeModel
{
	function __construct()
	{
		parent::__construct('table.tags','id');
	}
	
	public function listTags()
	{
		$args = array();
		$args['fields'] = '*,COUNT(table.post_tag_mapping) AS tag_sum';
		$args['table']	= 'table.tags LEFT JOIN table.post_tag_mapping ON table.tags.id = table.post_tag_mapping.id';
		$args['groupby'] = 'table.tags.id';
		return $this->fetch($args);
	}
	
	public function getTags($tags)
	{
		$tags = explode(",",$tags);
		$value = array();
		$where = array_fill(0,count($tags),'tag_name = BINARY ?');
		if($tags)
		{
			$result = 
			$this->fetch(array('table' => 'table.tags',
								'where' => array('template' => implode(" OR ",$where),
														   'value'	  => $tags
									)
									));
			foreach($result as $val)
			{
				$value[$val['id']] = $val['tag_name'];
			}
			foreach($tags as $val)
			{
				if(!in_array($val,$value))
				{
					$insertId = 
					$this->insertTable(array('tag_name' => $val,'tag_count' => 0));
					$value[$insertId] = $val;
				}
			}
		}
		return $value;
	}
	
	public function insertTags($id,$tags)
	{
		$tags = $this->getTags($tags);
		
		foreach($tags as $key => $val)
		{
			$this->insert(array('table' => 'table.post_tag_mapping',
										  'value' => array('post_id' => $id,'tag_id' => $key)
									));
		}
	}
	
	public function deleteTagsByPostId($id)
	{
		$this->delete(array('table' => 'table.post_tag_mapping',
									  'where' => array('template' => 'post_id = ?',
													   'value'	  => array($id)
								)
								));
	}
	
	public function tagClouds($limit,$order)
	{
		$args = array('table' => 'table.tags');
		if($limit)
		{
			$args['limit'] = $limit;
		}
		if($order)
		{
			$args['orderby'] = 'tag_count';
			$args['sort']      = 'DESC';
		}
		return $this->fetch($args);
	}
	
	public function getTagsByKeywords($keywords)
	{
		$this->clearArgs();
		$this->orderby = 'tag_count';
		$this->sort = 'DESC';
		
		$items = $this->fetchByFieldLike('tag_name',$keywords.'%',0,10);
		$result = array();
		
		foreach($items as $item)
		{
			$result[] = $item['tag_name'];
		}
		
		return $result;
	}
}
?>
