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
		
		//载入公用对象
		if(isset($args['public']))
		{
			$this->initPublicObject($args['public']);
		}

		//载入私有对象
		if(isset($args['private']))
		{
			$this->initPrivateObject($args['private']);
		}
	}

	protected function initPublicObject($public)
	{
		foreach($public as $objName)
		{
			eval("global \${$objName};");
			$isObject = false;
			$className = NULL;

			eval("\$className = get_class(\${$objName});");
			eval("if(NULL != \$className)" .
					"{" .
					"if(\${$objName} instanceof \$className) " .
					"{
						\$isObject = true;
					}}");
			if(!$isObject)
			{
					eval("\${$objName} = new ".ucfirst($objName)."();");
			}
			eval("\$this->{$objName} = \${$objName};");
		}
	}

	protected function initPrivateObject($private)
	{
		foreach($private as $objName)
		{
			eval("\$this->{$objName} = new ".ucfirst($objName)."();");
		}
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
