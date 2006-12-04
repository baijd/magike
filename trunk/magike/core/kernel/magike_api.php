<?php
/**********************************
 * Created on: 2006-12-1
 * File Name : magike_api.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class MagikeAPI
{
	public static function exceptionHandler($exception)
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

	public static function objectToModel($objName)
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

	public static function modelToFile($modelName)
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

	public static function exportArrayToFile($file,$array,$name)
	{
		file_put_contents($file,"<?php\n\$".$name." = ".var_export($array,true)."\n?>");
	}

	public static function subStr($str,$start,$end,$trim = "...")
	{
		preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $str, $info);
		$str = join("",array_slice($info[0],$start,$end));
		return ($end < (sizeof($info[0]) - $start)) ? $str.$trim : $str;
	}

	public static function strLen($str)
	{
		preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $str, $info);
		return sizeof($info[0]);
	}

	public static function stripTags($string)
	{
		$string=strip_tags($string);
		$string=str_replace(" ","",$string);
		$string=trim($string);

		return $string;
	}

	public static function errorHandler($errno, $errstr, $errfile, $errline)
	{
	 	switch ($errno)
	 	{
	 		case E_USER_ERROR:
	  			echo "<b>My ERROR</b> [$errno] $errstr<br />\n";
	   			echo "  Fatal error in line $errline of file $errfile";
	   			echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
	   			echo "Aborting...<br />\n";
	   			exit(1);
	  	 		break;
	 		case E_USER_WARNING:
	   			echo "<b>My WARNING</b> [$errno] $errstr<br />\n";
	   			break;
	 		case E_USER_NOTICE:
	   			echo "<b>My NOTICE</b> [$errno] $errstr<br />\n";
	   			break;
	 		default:
	   			echo "Unkown error type: [$errno] $errstr<br />\n";
	   			break;
	 	}
	}
}

function __autoload($className)
{
   $fileName = __DIR__."/model/".(MagikeAPI::modelToFile($className)).'.php';
   if(!file_exists($fileName))
   {
		MagikeObject::throwException(E_FILENOTEXISTS,array('class_name' => $className,
														   'file_name'  => $fileName));
   }
   require($fileName);
}
?>
