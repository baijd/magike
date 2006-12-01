<?php
/**********************************
 * Created on: 2006-11-30
 * File Name : module.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class MagikeObject
{
	public $stack;

	function __construct()
	{
		global $stack;
		$this->$stack = $stack;
	}

	public function setStack($stackType,$stackName,$stackValue)
	{
		global $stack;

		if(!isset($stack[$stackType])) $stack[$stackType] = array();
		$stack[$stackType][$stackName] = $stackValue;
		$this->stack = $stack;
	}

	public function setStackByType($stackType,$typeValue)
	{
		global $stack;
		$stack[$stackType] = $typeValue;
		$this->stack = $stack;
	}

	public function unsetStack($stackType,$stackName)
	{
		global $stack;
		unset($stack[$stackType][$stackName]);
		$this->stack = $stack;
	}

	public function throwException($message,$data = NULL,$callback = NULL)
	{
		throw new MagikeException($message,$data,$callback);
	}
}
?>
