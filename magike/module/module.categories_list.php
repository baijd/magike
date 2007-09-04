<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.categories_list.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class CategoriesList extends MagikeModule
{

	function __construct()
	{
		parent::__construct();
		$this->model = $this->loadModel('categories');
	}
	
	public function praseCategory($val)
	{
		$val["permalink"] = $this->stack['static_var']['index'].vsprintf($this->stack['permalink']['category']['path'],mgArrayIntersectKey($val,$this->stack['permalink']['category']['value']));
		return $val;
	}
	
	public function runModule()
	{
		return $this->model->listCategories(array('function' => array($this,'praseCategory')));
	}
}
?>
