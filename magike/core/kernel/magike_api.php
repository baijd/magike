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
			return substr(strtolower($modelName),1);
		}
	}

	public function exportArrayToFile($file,$array,$name)
	{
		file_put_contents($file,"<?php\n\$".$name." = ".var_export($array,true)."\n?>");
	}

	public function subStr($str,$start,$end,$trim = "...")
	{
		preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $str, $info);
		$str = join("",array_slice($info[0],$start,$end));
		return ($end < (sizeof($info[0]) - $start)) ? $str.$trim : $str;
	}

	public function strLen($str)
	{
		preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $str, $info);
		return sizeof($info[0]);
	}

	public function stripTags($string)
	{
		$string=strip_tags($string);
		$string=str_replace(" ","",$string);
		$string=trim($string);

		return $string;
	}
}

function __autoload($class_name)
{
   $fileName = __DIR__."/model/".(MagikeAPI::modelToFile($class_name)).'.php';
   if(!file_exists($fileName))
   {
		MagikeObject::throwException(E_FILENOTEXISTS,$fileName);
   }
   require($fileName);
}
?>
