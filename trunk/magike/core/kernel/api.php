<?php
/**********************************
 * Created on: 2006-12-1
 * File Name : api.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class API
{
	public function magikeExceptionHandler($exception)
	{
		if($exception->getCallback())
		{
			if(@function_exists($exception->getCallback()) || @method_exists($exception->getCallback()))
			{
				die(call_user_func($exception->getCallback(),$exception->getData()));
			}
			else
			{
				die($exception->__toString());
			}
		}
	}
}
?>
