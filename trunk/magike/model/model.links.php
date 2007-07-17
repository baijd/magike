<?php
/**********************************
 * Created on: 2006-12-3
 * File Name : model.links.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class LinksModel extends MagikeModel
{
	public function listLinks($limit = 20,$offset)
	{
		return $this->fetch(array('fields'=> '*,table.links.id AS link_id',
								   'table' => 'table.links JOIN table.link_categories ON table.links.link_category_id = table.link_categories.id',
								   'groupby' => 'table.links.id',
								   'offset' => $offset,
								   'limit' => $limit,
								   'orderby' => 'table.links.id',
								   'sort' => 'DESC'));
	}
	
	public function listLinksByCategory($key,$limit,$sort)
	{
		$this->clearArgs();
		if($sort != 'rand')
		{
			$this->orderby = 'id';
			$this->sort = $sort;
		}
		else
		{
			$this->sort = ' RAND()';
		}
		return $this->fetchByFieldEqual('link_category_id',$key,0,$limit);
	}
}
?>
