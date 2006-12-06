<?php
/**********************************
 * Created on: 2006-12-1
 * File Name : stack_model.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

//初始化全局变量stack
class StackModel extends MagikeObject
{
	public $data;

	function __construct()
	{
		$this->data = array();
	}

	public function setStack($stackType,$stackName,$stackValue)
	{
		if(!array_key_exists($stackType,$this->data))
		{
			$this->data[$stackType] = array();
		}
		$this->data[$stackType][$stackName] = $stackValue;
	}

	public function setStackByType($stackType,$typeValue)
	{
		$this->data[$stackType] = $typeValue;
	}

	public function pushStackByType($stackType,$typeValue)
	{
		if(!array_key_exists($stackType,$this->data))
		{
			$this->data[$stackType] = array();
		}
		$this->data[$stackType] = array_merge($this->data[$stackType],$typeValue);
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
?>
