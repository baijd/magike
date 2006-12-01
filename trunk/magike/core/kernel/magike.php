<?php
/**********************************
 * Created on: 2006-11-30
 * File Name : magike.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class Magike extends MagikeObject
{
	function __construct($args)
	{
		parent::__construct();
		$this->initDatabase();
		$this->initOptionValue();

		new ExceptionModel();
	}

	private function loadOptionCache()
	{
		new CacheModel(array(__CACHE__.'/system/option.php' => array('listener' => 'fileExists',
																	 'callback' => array($this,'buildCache'),
																	 'else '	=> array($this,'loadCache')
		)));
	}

	public function loadCache()
	{
		$option = array();
		require(__CACHE__.'/system/option.php');
		$this->setStackByType('option',$option);
	}

	public function buildCache()
	{
		$this->initOptionValue();
		exportArrayToFile($this->stack['option'],'option',__CACHE__.'/system/option.php');
	}

	private function initOptionValue()
	{
		global $database;
		
		$database = new DatabaseModel();
		$database->fectch(array('table' => 'table.option'),array($this,'pushOpitonValue'));
	}

	private function pushOpitonValue($val)
	{
		$this->setStack('option',$val['op_name'],$val['op_value']);
	}
}
?>
