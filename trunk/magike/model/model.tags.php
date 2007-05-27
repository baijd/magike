<?php
/**********************************
 * Created on: 2006-12-3
 * File Name : model.tags.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class TagsModel extends MagikeModel
{
	public function listTags()
	{
		$args = array();
		$args['fields'] = '*,COUNT(table.post_tag_mapping) AS tag_sum';
		$args['table']	= 'table.tags LEFT JOIN table.post_tag_mapping ON table.tags.id = table.post_tag_mapping.id';
		$args['groupby'] = 'table.tags.id';
		return $this->fectch($args);
	}
	
	private function getTags($tags)
	{
		$tags = explode(",",$tags);

		$where = array_fill(0,count($tags),'tag_name = ?');
		if($tags)
		{
			$value = array();
			$result = 
			$this->fectch(array('table' => 'table.tags',
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
					$this->insertTable(array('tag_name' => $val));
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
}
?>
