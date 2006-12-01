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

	public function objectToModel($objName)
	{
		if(NULL == $objName)
		{
			return NULL;
		}
		else
		{
			return ucfirst($objName).'Model';
		}
	}

	public function modelToFile($modelName)
	{
		if(NULL == $modelName)
		{
			return NULL;
		}
		else
		{
			$modelName = ereg_replace('([A-Z]+)','_\\1',$modelName);
			return substr($modelName,0,1);
		}
	}
}
?>