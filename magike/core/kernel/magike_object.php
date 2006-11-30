<?php
/**********************************
 * Created on: 2006-11-30
 * File Name : module.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class MagikeObject
{
	public $stack,$database;

	public function __construct()
	{
		global $stack,$database;
		$this->stack = $stack;
		$this->database = $database;
	}

	public function initStack($value)
	{
		global $stack;
		$stack = array_merge($stack,$value);
	}

	public function setStack($stackType,$stackName,$stackValue)
	{
		global $stack;

		if(!isset($stack[$stackType])) $stack[$stackType] = array();
		$stack[$stackType][$stackName] = $stackValue;
		$this->stack = $stack;
	}

	public function unsetStack($stackType,$stackName)
	{
		global $stack;
		unset($stack[$stackType][$stackName]);
		$this->stack = $stack;
	}

	public function _exception($info,$data,$callback = NULL)
	{
		if($callback && function_exists($callback)) die(call_user_func($callback));

		$display = "EXCEPTION: ";
		$display .= $info.",";

		if(is_array($data))
		{
			$display .= implode(",",$data);
		}
		else
		{
			$display .= $data;
		}
		die($display);
	}
}
?>
