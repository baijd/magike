<?php
/**********************************
 * Created on: 2006-12-3
 * File Name : model.link_categories.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class LinkCategoriesModel extends MagikeModel
{
	public function listLinkCategories()
	{
		return $this->fetch(array('table' => $this->table,
								   'orderby' => 'link_category_sort',
								   'sort' => 'ASC'));
	}
	
	public function listUnhiddenLinkCategories($func = NULL)
	{
		return $this->fetch(array('table' => $this->table,
								  'where'	=> array('template' => 'link_category_hide = 0'),
								   'orderby' => 'link_category_sort',
								   'sort' => 'ASC'),$func);
	}
	
	public function moveUpCategory($id)
	{
		$item = $this->fetchOneByKey($id);
		$title = $item['link_category_name'];
		$item = $this->fetchOne(array('table' 	=> 'table.link_categories',
											  'where'	=> array('template' => 'link_category_sort < ?',
																 'value'	=> array($item['link_category_sort'])
																),
											  'orderby'	=> 'link_category_sort',
											  'sort'	=> 'DESC',
											  'limit'	=> 1
											  )
										);
		if($item)
		{
			$this->increaseFieldByKey($item['id'],'link_category_sort');
			$this->decreaseFieldByKey($id,'link_category_sort');
		}
		
		return $title;
	}
	
	public function moveDownCategory($id)
	{
		$item = $this->fetchOneByKey($id);
		$title = $item['link_category_name'];
		$item = $this->fetchOne(array('table' 	=> 'table.link_categories',
											  'where'	=> array('template' => 'link_category_sort > ?',
																 'value'	=> array($item['link_category_sort'])
																),
											  'orderby'	=> 'link_category_sort',
											  'sort'	=> 'ASC',
											  'limit'	=> 1
											  )
										);
		if($item)
		{
			$this->decreaseFieldByKey($item['id'],'link_category_sort');
			$this->increaseFieldByKey($id,'link_category_sort');
		}
		
		return $title;
	}
}
?>
