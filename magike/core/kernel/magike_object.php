<?php
/**********************************
 * Created on: 2006-11-30
 * File Name : module.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class MagikeObject
{
	function __construct($args = array())
	{
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

	public function initPublicObject($public)
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
					eval("\${$objName} = new ".MagikeAPI::objectToModel($objName)."();");
			}
			eval("\$this->{$objName} = \${$objName};");
		}
	}

	public function initPrivateObject($private)
	{
		foreach($private as $objName)
		{
			eval("\$this->{$objName} = new ".MagikeAPI::objectToModel($objName)."();");
		}
	}

	public static function throwException($message,$data = NULL,$callback = NULL)
	{
		throw new MagikeException($message,$data,$callback);
	}

	public static function throwError($message,$type = E_USER_ERROR)
	{
		trigger_error($message,$type);
	}
}
?>
