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

	public function unsetStackByType($stackType)
	{
		unset($this->data[$stackType]);
	}
}

$stack = new StackModel();
?>
