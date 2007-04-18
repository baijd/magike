<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.categories_list.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class CategoriesList extends MagikeModule
{
	private $result;
	
	function __construct()
	{
		parent::__construct(array('public' => array('database')));
	}
	
	public function moveUpCategory()
	{
		$item = $this->database->fectch(array('table' => 'table.categories',
											  'where'	=> array('template' => 'id = ?',
																 'value'	=> array($_GET['c'])
										)));
		$item = $this->database->fectch(array('table' 	=> 'table.categories',
											  'where'	=> array('template' => 'category_sort < ?',
																 'value'	=> array($item[0]['category_sort'])
																),
											  'orderby'	=> 'category_sort',
											  'sort'	=> 'DESC',
											  'limit'	=> 1
											  )
										);
		
		if($item)
		{
			$this->database->update(array('table' => 'table.categories',
										  'where' => array('template' => 'id = ?',
															'value'	  => array($item[0]['id'])),
										  'value' => array('category_sort' => $item[0]['category_sort']+1)
										  ));
			$this->database->update(array('table' => 'table.categories',
										  'where' => array('template' => 'id = ?',
															'value'	  => array($_GET['c'])),
										  'value' => array('category_sort' => $item[0]['category_sort'])
										  ));
		}
	}
	
	public function moveDownCategory()
	{
		$item = $this->database->fectch(array('table' => 'table.categories',
											  'where'	=> array('template' => 'id = ?',
																 'value'	=> array($_GET['c'])
										)));
		$item = $this->database->fectch(array('table' 	=> 'table.categories',
											  'where'	=> array('template' => 'category_sort > ?',
																 'value'	=> array($item[0]['category_sort'])
																),
											  'orderby'	=> 'category_sort',
											  'sort'	=> 'ASC',
											  'limit'	=> 1
											  )
										);
		if($item)
		{
			$this->database->update(array('table' => 'table.categories',
										  'where' => array('template' => 'id = ?',
															'value'	  => array($item[0]['id'])),
										  'value' => array('category_sort' => $item[0]['category_sort']-1)
										  ));
			$this->database->update(array('table' => 'table.categories',
										  'where' => array('template' => 'id = ?',
															'value'	  => array($_GET['c'])),
										  'value' => array('category_sort' => $item[0]['category_sort'])
										  ));
		}
	}
	
	public function updateCategory()
	{
		$this->database->update(array('table' => 'table.categories',
									  'where' => array('template' => 'id = ?',
														'value'	  => array($_GET['c'])),
									  'value' => array('category_name' 		=> $_POST['category_name'],
													   'category_postname'	=> $_POST['category_postname'],
													   'category_describe'	=> $_POST['category_describe'])
									  ));
	}
	
	public function insertCategory()
	{
		$item = $this->database->fectch(array('fields'=> 'MAX(category_sort) AS max_sort',
											  'table' => 'table.categories'));
		$this->database->insert(array('table' => 'table.categories',
									  'value' => array('category_name' 		=> $_POST['category_name'],
													   'category_postname'	=> $_POST['category_postname'],
													   'category_describe'	=> $_POST['category_describe'],
													   'category_count'		=> 0,
													   'category_sort'		=> $item[0]['max_sort']+1)
									  ));
	}
	
	public function runModule()
	{
		$this->onGet("act","moveUpCategory","up");
		$this->onGet("act","moveDownCategory","down");
		$this->onGet("act","updateCategory","update");
		$this->onGet("act","insertCategory","insert");
		return $this->database->fectch(array('table' => 'table.categories','orderby' => 'category_sort','sort' => 'ASC'));
	}
}
?>
