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
	private $category;
	
	function __construct()
	{
		$this->result = array();
		parent::__construct();
		$this->category = $this->loadModel('categories');
	}
	
	public function displayCategory()
	{
		$this->result = $this->category->fetchOneByKey($_GET['c']);
		
		//修改菜单的内容
		if(isset($this->stack['admin_menu_list']['children']))
		{
			$this->stack['admin_menu_list']['children'][3]['menu_name'] = '编辑分类 "'.$this->result['category_name'].'"';
			$this->stack['admin_menu_list']['children'][3]['path_name'] = '/admin/posts/category/?c='.$this->result['id'];
			$this->stack['static_var']['admin_title'] = '编辑分类 "'.$this->result['category_name'].'"';
		}
	}
	
	public function runModule()
	{
		$this->onGet("c","displayCategory");
		return $this->result;
	}
}
?>
