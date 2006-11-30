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
		global $stack;

		//初始化堆栈,定义为全局变量
		$stack = array();
	}

	private function initOptionValue()
	{
		$this->database->fectch(array('table' => 'table.option'),array($this,'pushOpitonValue'));
	}

	private function pushOpitonValue($val)
	{
		$this->setStack($val['op_type'],$val['op_name'],$val['op_value']);
	}
}
?>
