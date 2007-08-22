<?php
/**********************************
 * Created on: 2007-2-2
 * File Name : lib.magike_object.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

abstract class MagikeObject
{
	public $stack;
	public $debugData;
	public $debugTime;
	
	function __construct($args = array())
	{
		//载入全局堆栈
		global $stack;
		$this->stack = &$stack;
	}

	protected static function throwException($message,$data = NULL,$code = 0,$callback = NULL)
	{
		//解决内存泄露的bug,不throw
		exceptionHandler(new MagikeException($message,$data,$code,$callback));
	}

	protected static function throwError($message,$type = E_USER_ERROR)
	{
		trigger_error($message,$type);
	}
}
?>
