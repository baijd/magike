<?php
/**********************************
 * Created on: 2006-12-3
 * File Name : model.categories.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class CategoriesModel extends MagikeModel
{
	function __construct()
	{
		parent::__construct('table.categories','id');
	}
	
	public function listCategories($func = NULL)
	{
		return $this->fetch(array('table' => 'table.categories','orderby' => 'category_sort','sort' => 'ASC'),$func);
	}
	
	public function moveUpCategory($id)
	{
		$item = $this->fetchOneByKey($id);
		$title = $item['category_name'];
		$item = $this->fetchOne(array('table' 	=> 'table.categories',
											  'where'	=> array('template' => 'category_sort < ?',
																 'value'	=> array($item['category_sort'])
																),
											  'orderby'	=> 'category_sort',
											  'sort'	=> 'DESC',
											  'limit'	=> 1
											  )
										);
		if($item)
		{
			$this->increaseFieldByKey($item['id'],'category_sort');
			$this->decreaseFieldByKey($id,'category_sort');
		}
		
		return $title;
	}
	
	public function moveDownCategory($id)
	{
		$item = $this->fetchOneByKey($id);
		$title = $item['category_name'];
		$item = $this->fetchOne(array('table' 	=> 'table.categories',
											  'where'	=> array('template' => 'category_sort > ?',
																 'value'	=> array($item['category_sort'])
																),
											  'orderby'	=> 'category_sort',
											  'sort'	=> 'ASC',
											  'limit'	=> 1
											  )
										);
		if($item)
		{
			$this->decreaseFieldByKey($item['id'],'category_sort');
			$this->increaseFieldByKey($id,'category_sort');
		}
		
		return $title;
	}
}
?>
