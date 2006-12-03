<?php
/**********************************
 * Created on: 2006-12-1
 * File Name : magike_api.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class MagikeAPI
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
			$modelName = ereg_replace("([A-Z]+)","_\\1",$modelName);
			return substr(strtolower($modelName),0,1);
		}
	}

	public function exportArrayToFile($file,$array)
	{
		file_put_contents($file,"<?php\n".var_export($array,true)."\n?>");
	}
}

function __autoload($class_name)
{
   $fileName = __DIR__."/model/".(MagikeAPI::modelToFile($class_name)).'.php';
   if(!file_exists($fileName))
   {
		MagikeObject::throwException(E_FILENOTEXISTS,$fileName);
   }
   require_once($fileName);
}
?>
