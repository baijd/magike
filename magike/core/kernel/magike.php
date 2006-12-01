<?php
/**********************************
 * Created on: 2006-11-30
 * File Name : magike.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class Magike
{
	function __construct($args)
	{
		global $stack;

		//初始化堆栈
		$stack = array();
		$this->loadStaticCache();
	}

	private function loadStaticCache()
	{
		new CacheModel(array(__CACHE__.'/system/static.php' => array('listener' => 'fileExists',
																	 'callback' => array($this,'buildCache'),
																	 'else '	=> array($this,'loadCache')
		)));
	}

	public function loadCache()
	{
		global $stack;

		$static = array();
		require(__CACHE__.'/system/static.php');
		$stack['static'] = $static;
	}

	public function buildCache()
	{
		global $stack;
		$this->initStaticValue();
		exportArrayToFile($stack['static'],'static',__CACHE__.'/system/static.php');
	}

	private function initStaticValue()
	{
		global $database;

		$database = new DatabaseModel();
		$database->fectch(array('table' => 'table.static'),array($this,'pushStaticValue'));
	}

	private function pushStaticValue($val)
	{
		global $stack;
		$stack['static'][$val['st_name']] = $val['st_value'];
	}
}
?>
