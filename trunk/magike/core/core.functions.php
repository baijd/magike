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
function mgReplaceVar($str,$var)
{
	if(preg_match("/\\$([_0-9a-zA-Z-\.]+)/is",$str,$matches))
	{
		array_shift($matches);
		foreach($matches as $match)
		{
			$str = str_replace('$'.$match,$var[$match],$str);
		}
	}
	
	return $str;
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
			require($dir.'/'.$file);
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
?>