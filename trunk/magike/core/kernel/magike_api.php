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
			$callback = $exception->getCallback();
			$functionExists = is_array($callback) ? method_exists($callback[0],$callback[1]) : function_exists($callback);
			if($functionExists)
			{
				die(call_user_func($callback,$exception->getData()));
			}
			else
			{
				die($exception->__toString());
			}
		}
		else
		{
			die($exception->__toString());
		}
	}

	public static function array_intersect_key($array1, $array2)
	{
		$result = array();
		foreach($array1 as $key=>$val)
		{
			if(array_key_exists($key, $array2))
			{
				$result[$key] = $array1[$key];
			}
		}
		return $result;
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

	public static function mkdir($inpath,$mode = 0777)
	{
		$inpath = str_replace('\\','/',$inpath);
		$inpath = str_replace('//','/',$inpath);
		$dir = explode('/',$inpath);
		$dirs = array();

		foreach($dir as $key => $val)
		{
			array_push($dirs,$val);
			$path = implode('/',$dirs);

			if(!is_dir($path))
			{
				if(mkdir($path,$mode) == false) return false;
			}
		}

		return true;
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

	public static function fileToModule($fileName)
	{
		return ucfirst(preg_replace_callback("/[\_]([a-z])/i",array('MagikeAPI','fileToModuleCallback'),$fileName));
	}

	public static function fileToModuleCallback($matches)
	{
		return strtoupper($matches[1]);
	}

	public static function exportArrayToFile($file,$array,$name)
	{
		if(!is_dir(dirname($file)))
		{
			self::mkdir(dirname($file));
		}

		file_put_contents($file,"<?php\n\$".$name." = ".var_export($array,true).";\n?>");
		file_put_contents($file,php_strip_whitespace($file));
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

	public static function getFile($inpath,$trim = false,$stamp = NULL)
	{
        $file = array();

		if(!is_dir($inpath))
		{
			return $file;
		}

		$handle=opendir($inpath);
        if($stamp != NULL)
        {
        	$stamp = explode("|",$stamp);
        }

        while ($tmp = readdir($handle)) {
            if(is_file($inpath."/".$tmp) && eregi("^([_@0-9a-zA-Z\x80-\xff\^\.\%-]{0,})[\.]([0-9a-zA-Z]{1,})$",$tmp,$file_name))
            {
            	if($stamp != NULL && in_array($file_name[2],$stamp))
            	{
            		$file[] = $trim ? $file_name[0] : $file_name[1];
            	}
            	else if($stamp == NULL)
            	{
            		$file[] = $trim ? $file_name[0] : $file_name[1];
            	}
            }
        }
        closedir($handle);
        return $file;
	}

	public static function replaceArray($value,$before,$after)
	{
        foreach($value as $key=>$val)
        {
              $value[$key] = $before.$val.$after;
        }
        return $value;
	}

	public static function ip2long($ip)
	{
		return sprintf("%u",ip2long($ip));
	}

	public static function createRandomString($number)
    {
        $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
        $result = '';
        for($i=0;$i<$number;$i++)
        {
            $result .= $str[rand(0,52)];
        }
        return $result;
    }

	public static function errorHandler($errno, $errstr, $errfile, $errline)
	{
	 	switch ($errno)
	 	{
	 		case E_USER_ERROR:
	  			echo "Fatal Error type[$errno]: [$errfile][line:$errline] $errstr<br />\n";
	   			exit(1);
	  	 		break;
	 		case E_USER_WARNING:
	   			echo "Warning type[$errno]: [$errfile][line:$errline] $errstr<br />\n";
	   			break;
	 		case E_USER_NOTICE:
	   			echo "Notice type[$errno]: [$errfile][line:$errline] $errstr<br />\n";
	   			break;
	 		default:
	   			echo "Unkown error type[$errno]: [$errfile][line:$errline] $errstr<br />\n";
	   			break;
	 	}
	}

	public static function error404Callback($path)
	{
		echo $path;
	}
}

function __autoload($className)
{
   $fileName = __DIR__.'/model/'.(MagikeAPI::modelToFile($className)).'.php';
   if(!file_exists($fileName))
   {
		$fileName = __MODULE__.'/'.(MagikeAPI::modelToFile($className)).'.php';
		if(!file_exists($fileName))
		{
		MagikeObject::throwException(E_FILENOTEXISTS,array('class_name' => $className,
														   'file_name'  => $fileName));
		}
		else
		{
			global $stack;
			if(isset($stack->data['system']['template']))
			{
				$incStr  = file_get_contents(__COMPILE__.'/'.$stack->data['system']['template'].'.inc.php');
				$incStr .= php_strip_whitespace($fileName);
				file_put_contents(__COMPILE__.'/'.$stack->data['system']['template'].'.inc.php',$incStr);
			}
		}
   }
   require($fileName);
}
?>
