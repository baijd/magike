<?php
/**********************************
 * Created on: 2006-12-1
 * File Name : stack_model.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

//初始化全局变量stack
global $stack;
class StackModel extends MagikeObject
{
	public $data;

	function __construct()
	{
		$this->data = array();
		$this->loadStaticValue();
	}

	//载入静态变量
	private function loadStaticValue()
	{
		//监听缓存文件
		new CacheModel(array(__CACHE__.'/system/static.php' => array('listener' => 'fileExists',
																	 'callback' => array($this,'buildCache'),
																	 'else '	=> array($this,'loadCache')
		)));
	}

	public function setStack($stackType,$stackName,$stackValue)
	{
		if(!isset($this->data[$stackType]))
		{
			$this->data[$stackType] = array();
		}
		$this->data[$stackType][$stackName] = $stackValue;
	}

	public function setStackByType($stackType,$typeValue)
	{
		$this->data[$stackType] = $typeValue;
	}

	public function unsetStack($stackType,$stackName)
	{
		unset($this->data[$stackType][$stackName]);
	}

	public function loadCache()
	{
		$static = array();
		require(__CACHE__.'/system/static.php');
		$this->data['static'] = $static;
	}

	public function buildCache()
	{
		$this->initStaticValue();
		API::exportArrayToFile($this->data['static'],'static',__CACHE__.'/system/static.php');
	}

	private function initStaticValue()
	{
		$this->data['static'] = array();
		$database->fectch(array('table' => 'table.static'),array($this,'pushStaticValue'));
	}

	private function pushStaticValue($val)
	{
		$this->data['static'][$val['st_name']] = $val['st_value'];
	}
}

$stack = new StackModel();
?>
