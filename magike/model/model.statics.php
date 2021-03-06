<?php
/**********************************
 * Created on: 2006-12-3
 * File Name : model.statics.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class StaticsModel extends MagikeModel
{
	function __construct()
	{
		parent::__construct('table.statics','id');
	}
	
	public function increaseValueByName($name,$num = 1)
	{
 	 	 return $this->increaseField(array('table' => $this->table,
 	 	 								   'where' => array('template' => "static_name = ?",
 	 	 													'value' => array($name))),
 	 	 							'static_value',$num);
	}
	
	public function decreaseValueByName($name,$num = 1)
	{
 	 	 return $this->decreaseField(array('table' => $this->table,
 	 	 								   'where' => array('template' => "static_name = ?",
 	 	 													'value' => array($name))),
 	 	 							'static_value',$num);
	}
	
	public function listStaticVars($func = NULL)
	{
		$this->fetch(array('table' => 'table.statics'),$func);
	}
}
?>
