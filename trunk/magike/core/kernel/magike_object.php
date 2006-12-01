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
		if(isset($args['require']))
		{
			$this->initRequiredObject($args['require']);
		}
	}

	private function initRequiredObject($require)
	{
		foreach($require as $objName)
		{
			eval("global \${$objName};");
			if(is_a((object) $objName,MagikeAPI::objectToModel($objName)))
			{
				eval("\$this->{$objName} = \${$objName}");
			}
			else
			{
				eval("unset(\${$objName})");
				$this->throwException(E_OBJECTNOTEXISTS,$objName);
			}
		}
	}

	public function throwException($message,$data = NULL,$callback = NULL)
	{
		throw new MagikeException($message,$data,$callback);
	}
}
?>
