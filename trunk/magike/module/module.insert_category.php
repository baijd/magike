<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.insert_category.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class InsertCategory extends MagikeModule
{
	private $result;
	
	function __construct()
	{
		$this->result = array();
		parent::__construct(array('public' => array('database')));
	}
	
	public function displayCategory()
	{
		$this->result = $this->database->fectchOne(array('table' => 'table.categories',
											  'where'	=> array('template' => 'id = ?',
																 'value'	=> array($_GET['c'])
										)));
		
		//修改菜单的内容
		if(isset($this->stack['admin_menu_list']['children']))
		{
			$this->stack['admin_menu_list']['children'][3]['menu_name'] = '修改分类 "'.$this->result['category_name'].'"';
			$this->stack['admin_menu_list']['children'][3]['path_name'] = '/admin/posts/category?c='.$this->result['id'];
		}
	}
	
	public function runModule()
	{
		$this->onGet("c","displayCategory");
		return $this->result;
	}
}
?>