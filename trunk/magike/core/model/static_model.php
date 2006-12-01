<?php
/**********************************
 * Created on: 2006-12-1
 * File Name : static_model.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class StaticModel extends MagikeObject
{
	function __construct()
	{
 		//获取实例化的stack和database
		parent::__construct(array('require' => array('stack',
													 'database')));
		new CacheModel(array(__CACHE__.'/system/static.php' => array('listener' => 'fileExists',
																	 'callback' => array($this,'buildCache'),
																	 'else '	=> array($this,'loadCache')
		)));
	}

	public function loadCache()
	{
		$static = array();
		require(__CACHE__.'/system/static.php');
		$this->stack->setStackByType('static',$static);
	}

	public function buildCache()
	{
		$this->initStaticValue();
		MagikeAPI::exportArrayToFile($this->stack->data['static'],'static',__CACHE__.'/system/static.php');
	}

	private function initStaticValue()
	{
		$this->database->fectch(array('table' => 'table.static'),array($this,'pushStaticValue'));
	}

	private function pushStaticValue($val)
	{
		$this->stack->setStack('static',$val['st_name'],$val['st_value']);
	}
}
?>
