<?php
/**********************************
 * Created on: 2007-2-2
 * File Name : core.functions.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

//将按照骆驼法则命名的类名称转化为以下划线法则命名的文件名
function mgClassNameToFileName($className)
{
	$fileName = ereg_replace("([A-Z]+)","_\\1",ucfirst($className));
	return substr(strtolower($fileName),1);
}

//将按照下划线法则命名的文件名转化为以骆驼法则命名的类名
function mgFileNameToClassName($fileName,$isObject = true)
{
	return $isObject ? ucfirst(preg_replace_callback("/[\_]([a-z])/i",'mgFileNameToClassNameCallback',$fileName))
	: preg_replace_callback("/[\_]([a-z])/i",'mgFileNameToClassNameCallback',$fileName);
}

function mgFileNameToClassNameCallback($matches)
{
	return strtoupper($matches[1]);
}

//将路径名转化为一个经过压缩的唯一文件名
function mgPathToFileName($path)
{
	return strtolower(preg_replace("/([\/\\\.])/is",'%',$path));
}

//替换字符串中的变量
function mgReplaceVar($str,$var,$explode = '.')
{
	if(preg_match("/\{\\$([_0-9a-zA-Z-\.]+)\}/is",$str,$matches))
	{
		array_shift($matches);
		foreach($matches as $match)
		{
			$str = str_replace('{$'.$match.'}',mgReplaceVarCallback(explode($explode,$match),$var),$str);
		}
	}
	
	return $str;
}

function mgReplaceVarCallback($partten,$replace)
{
	$current = array_shift($partten);
	if(NULL != $partten && is_array($replace[$current]))
	{
		return mgReplaceVarCallback($partten,$replace[$current]);
	}
	else if(NULL == $partten && isset($replace[$current]))
	{
		return $replace[$current];
	}
	else
	{
		return NULL;
	}
}

//适用于utf8的字符串函数
function mgSubStr($str,$start,$end,$trim = "...")
{
	preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $str, $info);
	$str = join("",array_slice($info[0],$start,$end));
	return ($end < (sizeof($info[0]) - $start)) ? $str.$trim : $str;
}

function mgStrLen($str)
{
	preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $str, $info);
	return sizeof($info[0]);
}

function mgStripTags($string)
{
	$string=strip_tags($string);
	$string=str_replace(" ","",$string);
	$string=trim($string);

	return $string;
}

//一次创建一个目录树
function mgMkdir($inpath,$mode = 0777)
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

//一次包含多个类库
function mgRequireObjects($dir,$template)
{
	$objects = array();
	$files = mgGetFile($dir,true);
	
	foreach($files as $file)
	{
		if(preg_match($template,$file,$matches))
		{
			require_once($dir.'/'.$file);
			$objects[] = mgFileNameToClassName($matches[1]);
		}
	}
	
	return $objects;
}

//一次创建多个对象
function mgCreateObjects($objects,$method = null,$args = array())
{
	foreach($objects as $object)
	{
		$tmp = null;
		eval('$tmp = new '.$object.'();');
		if($method)
		{
			if(isset($args[$object]))
			{
				call_user_func(array($tmp,$method),$args[$object]);
			}
			else
			{
				call_user_func(array($tmp,$method));
			}
		}
	}
}

//获取一个目录下的文件
function mgGetFile($inpath,$trim = false,$stamp = NULL)
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

//将数组输出到一个文件中
function mgExportArrayToFile($file,$array,$name)
{
	if(!is_dir(dirname($file)))
	{
		mgMkdir(dirname($file));
	}

	file_put_contents($file,"<?php\n\$".$name." = ".var_export($array,true).";\n?>");
}

//将ip转化为长整型字符串
function mgIpToLong($ip)
{
	return sprintf("%u",ip2long($ip));
}

//创建一个任意长度的随机字符串
function mgCreateRandomString($number)
{
    $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
    $result = '';
    for($i=0;$i<$number;$i++)
    {
        $result .= $str[rand(0,52)];
    }
    return $result;
}

//获取毫秒级时间
function mgGetMicrotime()
{
	return microtime(true); 
}

//获取当前当地时间戳
function mgTime($UTC)
{
	return time() + $UTC;
}

//获取当前时区时间差
function mgGetTimeZoneDiff()
{
	return time() - strtotime(gmdate('Y-m-d H:i:s'));
}

//将时间戳格式化
function mgDate($fmt,$UTC = 0,$timestamp = NULL)
{
	if($timestamp)
	{
		return date($fmt,$timestamp + $UTC);
	}
	else
	{
		return date($fmt,mgTime($UTC));
	}
}
?>
