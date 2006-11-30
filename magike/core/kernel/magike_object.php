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
	public $database;

	public function __construct()
	{
		global $stack;
		$this->stack = $stack;
	}

	public function initDatabase()
	{
		global $database;

		if(!is_a($database,'DatabaseModel'))
		{
			$database = new DatabaseModel();
		}
		$this->database = $database;
	}

	public function initStack($value)
	{
		global $stack;
		$stack = $value;
		$this->stack = $stack;
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
