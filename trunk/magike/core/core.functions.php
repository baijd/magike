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
	return str_replace("_","",$fileName);
}

function mgFileNameToUniqueClassName($className)
{
	$path = explode('.',$className);
	$class = array_pop($path);
	array_push($path,mgFileNameToClassName($class));
	return implode('__',$path);
}

//获取对象类型
function mgGetObjectClass($object)
{
	$require = array('MagikeObject','MagikeModule','MagikeModel');
	
	$object = get_class($object);
	do
	{
		if(in_array($object,$require))
		{
			break;
		}
	}
	while($object = get_parent_class($object));
	return $object;
}

//将路径名转化为一个经过压缩的唯一文件名
function mgPathToFileName($path)
{
	return strtolower(str_replace(array("/","\\","."),'%',$path));
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

//替换字符串中的变量
function mgPraseVar($str,$var,$explode = '.')
{
        if(preg_match("/\{\\$([_0-9a-zA-Z-\.]+)\}/is",$str,$matches))
        {
                array_shift($matches);
                foreach($matches as $match)
                {
                        $str = str_replace('{$'.$match.'}','{'.$var."['".str_replace('.',"']['",$match)."']}",$str);
                }
        }

        return $str;
}


//适用于utf8的字符串函数

if(function_exists('mb_get_info'))
{
	function mgSubStr($str,$start,$length,$trim = "...")
	{
		$iLength = mb_strlen($str);
		$str = mb_substr($str,$start,$length);
		return ($length < $iLength - $start) ? $str.$trim : $str;
	}

	function mgStrLen($str)
	{
		return mb_strlen($str);
	}
}
else
{
	function mgSubStr($str,$start,$length,$trim = "...")
	{
		preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $str, $info);
		$str = join("",array_slice($info[0],$start,$length));
		return ($length < (sizeof($info[0]) - $start)) ? $str.$trim : $str;
	}

	function mgStrLen($str)
	{
		preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/", $str, $info);
		return sizeof($info[0]);
	}
}

function mgStripTags($string)
{
	$string = strip_tags($string);
	$string = trim($string);
	$string = str_replace(array("\r\n\r\n","\n\n"),"",$string);

	return $string;
}

function mgGetScript($string)
{
	$start = strpos($string,"<?php");
	$stop	= strrpos($string,"?>");
	return substr($string,$start,$stop - $start + 2);
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
		$dirs[] = $val;
		$path = implode('/',$dirs);

		if(!is_dir($path))
		{
			if(mkdir($path,$mode) == false) return false;
		}
	}

	return true;
}

function mgRmDir($inpath)
{
	str_replace("//","/",$inpath);
	$dir = explode("/",$inpath);
	
	foreach($dir as $key => $val)
	{
		$path = implode("/",$dir);
		if(false == mgUnLink($path)) 
		{
			return false;
		}
		if(NULL != ($dirs = mgGetDir($path)))
		{
			foreach($dirs as $inkey => $inval)
			{
				if(mgRmDir($path."/".$inval) == false) return false;
			}
		}
		if(false == @rmdir($path))
		{
			return false;
		}
		if($inpath != $path)
		{
			array_pop($dir);
		}
		else break;
	}
	
	return true;
}

function mgUnLink($inpath)
{
	str_replace("//","/",$inpath);
	$files = mgGetFile($inpath,true);
	
	if(NULL != $files)
	{
		foreach($files as $key => $val)
		{
		if(@unlink($inpath."/".$val) == false) return false;
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
		$tmp = new $object();
		if($method)
		{
			if(isset($args[$object]))
			{
				$tmp->$method($args[$object]);
			}
			else
			{
				$tmp->$method();
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
	if(NULL != $stamp)
	{
		$stamp = explode("|",$stamp);
	}

	while ($tmp = readdir($handle)) 
	{
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

//获取一个目录下的目录
function mgGetDir($inpath)
{
	$handle=opendir($inpath);
	$dir = array();
	while ($tmp = readdir($handle))
	{
		if(is_dir($inpath."/".$tmp) && $tmp != ".." && $tmp != "." && 0 !== stripos($tmp,'.')) 
		{
			$dir[] = $tmp;
		}
	}
	closedir($handle);
	return $dir;
}

//将数组输出到一个文件中
function mgExportArrayToFile($file,$array,$name,$quotes = false)
{
	if(!is_dir(dirname($file)))
	{
		mgMkdir(dirname($file));
	}

	$var = var_export($array,true);
	if($quotes)
	{
		$var = str_replace("'",'"',$var);
		$var = str_replace('\"',"'",$var);
	}

	file_put_contents($file,"<?php\n\$".$name." = ".$var.";\n?>");
}

//获取当前path
function mgGetPathInfo()
{
	$path = "";
	
	if(isset($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO'])
	{
		$path = $_SERVER['PATH_INFO'];
	}
	else if(isset($_SERVER['ORIG_PATH_INFO']) && $_SERVER['ORIG_PATH_INFO'])
	{
		if(strpos($_SERVER['ORIG_PATH_INFO'],$_SERVER['PHP_SELF']) === 0)
		{
			$len = strlen($_SERVER['PHP_SELF']);
			$path = substr($_SERVER['ORIG_PATH_INFO'],$len+1,strlen($_SERVER['ORIG_PATH_INFO']) - $len);
		}
		else
		{
			$path = $_SERVER['ORIG_PATH_INFO'];
		}
	}
	
	
	if($path == "")
	{
		$path = '/';
	}
	
	return $path;
}

//将ip转化为长整型字符串
function mgIpToLong($ip)
{
	return sprintf("%u",ip2long($ip));
}

function mgTrace($inOut = true)
{
	if(__DEBUG__)
	{
		global $debugData,$debugTime;
		if($inOut)
		{
			$debugTime['now'][] = mgGetMicrotime() - $debugTime['start'];
		}
		else
		{
			$debugTime['time'] = array_pop($debugTime['now']);
			$debugTime['last'] = mgGetMicrotime() - $debugTime['start'] - $debugTime['time'];
		}
	}
}

function mgDebug($message,$object = NULL)
{
	if(__DEBUG__)
	{
		global $debugData,$debugTime;
		$debug = array();
		if(is_string($object) && $object)
		{
			$debug['object'] = $object;
			$debug['parent'] = 'Stream';
			
		}
		else
		{
			$debug['object'] = get_class(NULL == $object ? $this : $object);
			$debug['parent'] = mgGetObjectClass(NULL == $object ? $this : $object);
		}
		$debug['time'] = $debugTime['time'];
		$debug['last'] = $debugTime['last'];
		$debug['message'] = $message;
		$debugData[] = $debug;
	}
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

//获得一个guid
function mgGetGuid()
{
	return md5(uniqid(time()));
}

//获取一个散列路径
function mgGetGuidPath($guid)
{
	$path1 = substr($guid,0,2);
	$path2 = substr($guid,2,2);
	
	return '/'.$path1.'/'.$path2;
}

//打印调试信息
function mgPrintDebug($path,$debug)
{
	$str = '';
	
	if(__DEBUG_SORT_BY_TIME__)
	{
		$sum = count($debug);
		for($i = 0;$i < $sum;$i++)
		{
			for($j = 0;$j < $sum;$j++)
			{
				if($debug[$i]['last'] > $debug[$j]['last'])
				{
					$tmp = $debug[$i];
					$debug[$i] = $debug[$j];
					$debug[$j] = $tmp;
					reset($debug);
				}
			}
		}
	}
	
	foreach($debug as $val)
	{
		if(((__DEBUG_MESSAGE_FILTER__ && __DEBUG_MESSAGE_FILTER__ == $val['message']) || !__DEBUG_MESSAGE_FILTER__)
		&& __DEBUG_TIME_FILTER__ < $val['last'])
		{
			$str .= 'Message:'.$val['message']."\r\n";
			$str .= $val['parent'].':'.$val['object']."\r\n";
			$str .= 'TimeLine:'.$val['time']." Seconds\r\n";
			$str .= 'LastTime:'.$val['last']." Seconds\r\n";
			$str .= "\r\n\r\n";
		}
	}
	
	file_put_contents($path,$str);
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

//是否是ajax表单提交
function mgIsAjaxForm()
{
	return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 'XMLHttpRequest' == $_SERVER['HTTP_X_REQUESTED_WITH'] ? true : false;
}

//分析response参数
function mgGetHttpResponse($response)
{
	$rows = explode("\r\n\r\n",$response);
	
	$header = isset($rows[0]) ? trim($rows[0]) : NULL;
	if(NULL == $header)
	{
		return false;
	}

	$result = array();
	array_shift($rows);
	$body = implode("\r\n",$rows);
	$result["body"] = $body;
	
	$headerRows = explode("\r\n",str_replace("  "," ",trim($header)));
	$status = explode(" ",array_shift($headerRows));
	$result['status'] = intval(trim($status[1]));
	
	foreach($headerRows as $val)
	{
		$item = explode(":",$val);
		$key = strtolower(trim(array_shift($item)));
		$result[$key] = trim(implode(':',$item));
	}
	
	return $result;
}

//http发送函数
if(function_exists('curl_version'))
{
	function mgHttpSender($url,$agent = NULL,$getData = NULL,$postData = NULL,$timeOut = 5,$locationTimes = 0)
	{
		//check locationTimes
		if($locationTimes >= 3)
		{
			return NULL;
		}
		
		//get user agent
		$agent = (NULL == $agent) ? $_SERVER['HTTP_USER_AGENT'] : $agent;
		
		if($url)
		{
			//building get string
			$ch = curl_init();
			$url = $url.(NULL == $getData ? NULL : ((false != strpos($url,"?") ? "&" : "?").http_build_query($getData)));
			
			//setting http header
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, $agent);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch,CURLOPT_AUTOREFERER,1);
			curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeOut);
			curl_setopt($ch,CURLOPT_TIMEOUT,$timeOut);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch,CURLOPT_HEADER,1);
			
			//set method to post
			if (NULL != $postData)
			{
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
			}
			
			$data = curl_exec($ch);
			curl_close($ch);
			$response = mgGetHttpResponse($data);
			
			//do location
			if(isset($response['location']) && $response['location'] != $url)
			{
				return mgHttpSender($response['location'],$agent,$getData,$postData,$timeOut,$locationTimes+1);
			}
			else
			{
				return $response;
			}
		}
		else
		{
			return NULL;
		}
	}
}
else
{
	//$locationTimes is a hidden parm
	function mgHttpSender($url,$agent = NULL,$getData = NULL,$postData = NULL,$timeOut = 5,$locationTimes = 0)
	{
		//check locationTimes
		if($locationTimes >= 3)
		{
			return NULL;
		}
	
		//get user agent
		$agent = (NULL == $agent) ? $_SERVER['HTTP_USER_AGENT'] : $agent;
		
		if($url)
		{
			$url = $url.(NULL == $getData ? NULL : ((false != strpos($url,"?") ? "&" : "?").http_build_query($getData)));
			$parsedUrl = parse_url($url);
			if ($parsedUrl['host'] == '')
			{
				return false;
			}
			
			$port = isset($parsedUrl['port']) ? $parsedUrl['port'] : 80;
			
			if(NULL != $postData)
			{
				$request  = 'POST ' . $parsedUrl['path'];
			}
			else
			{
				$request  = 'GET ' . $parsedUrl['path'];
			}
			
			if (isset($parsedUrl['query']))	
			{
				$request .= '?' . $parsedUrl['query'];
			}
			
			$request .= " HTTP/1.1\r\n";
			$request .= "Accept: */*\r\n";
			$request .= "User-Agent: " . $agent . "\r\n";
			$request .= "Host: " . $parsedUrl['host'] . ":" . $port . "\r\n";
			$request .= "Connection: Keep-Alive\r\n";
			$request .= "Keep-Alive: 300\r\n";
			$request .= "Cache-Control: no-cache\r\n";
			$request .= "Connection: Close\r\n";
			
			if(NULL != $postData)
			{
				$content = http_build_query($postData);
				$request .= "Content-Length: " . strlen($content) . "\r\n";
				$request .= "Content-Type: application/x-www-form-urlencoded\r\n";
				$request .= "\r\n";
				$request .= $content;
			}
			else
			{
				$request .= "\r\n";
			}
			
			$socket = @fsockopen($parsedUrl['host'], $port, $errno, $errstr,$timeOut);
			if(!$socket)
			{
				return false;
			}
			
			//sending it
			fputs( $socket, $request);
			stream_set_timeout($socket, 5);
			$info = stream_get_meta_data($socket);
			
			$data = "";

			//get response
			while ( ! feof ( $socket )  && !$info['timed_out'] )
			{
				$data .= fgets( $socket, 4096 );
			}
			
			fclose($socket);
			$response = mgGetHttpResponse($data);
			
			//do location
			if(isset($response['location']) && $response['location'] != $url)
			{
				return mgHttpSender($response['location'],$agent,$getData,$postData,$timeOut,$locationTimes+1);
			}
			else
			{
				return $response;
			}
		}
		else
		{
			return NULL;
		}
	}
}

//tackback提交函数
function mgSendTrackback($url,$args)
{
	if($url)
	{
		$urls = explode("\n",$url);
		$result = array();
		$agent  = $args['agent'];
		unset($args['agent']);
		reset($args);

		//send information
		foreach($urls as $val)
		{
			unset($args['agent']);
			$data = mgHttpSender(trim($val),$agent,$args);
			if(NULL == $data)
			{
				$result[$val] = false;
				continue;
			}
			if(200 != $data['status'])
			{
				$result[$val] = false;
				continue;
			}
			
			$response = $data['body'];
			//here is response
			if ( strstr($response,'<error>1</error>') )
			{
				$result[$val] = false;
				continue;
			}
			else if ( strstr($response,'<error>0</error>') )
			{
				$result[$val] = true;
				continue;
			}
			else if ( !strstr($response,'<error>') )
			{
				$result[$val] = false;
				continue;
			}
		}
		return $result;
	}

	return array();
}

function mgCheckPingback($url)
{
	
}

//this function is powered by others
function mgAddslashesDeep($value)
{
    return    is_array($value) ?
                array_map('mgAddslashesDeep', $value) :
                addslashes($value);
}

//this function is powered by others
function mgStripslashesDeep($value)
{
    return    is_array($value) ?
                array_map('mgStripslashesDeep', $value) :
                stripslashes($value);
}

function mgSprintf($args , $format)
{
	if(is_array($args))
	{
		$result = array();
		foreach($args as $val)
		{
			$tmp = "";
			eval('$tmp = sprintf($format,"'.$val.'");');
			$result[] = $tmp;
		}
		return $result;
	}
	else
	{
		$tmp = "";
		eval('$tmp = sprintf($format,'.$args.');');
		return $tmp;
	}
}

function mgStringRelplace($src , $dst, $str)
{
	if(is_array($dst))
	{
		$result = array();
		foreach($dst as $val)
		{
			$result[] = str_replace($src,$val,$str);
		}
		return $result;
	}
	else
	{
		return str_replace($src,$dst,$str);
	}
}

if (!function_exists('array_intersect_key')) {
	function array_intersect_key($arr1, $arr2) 
	{
		$res = array();
		foreach($arr1 as $key=>$value) 
		{
			if(array_key_exists($arr2[$key])) 
			{
				$res[$key] = $arr1[$key];
			}
		}
		return $res;
	}
}

function mgArrayIntersectKey($arr1, $arr2) 
{
	$res = array();
	foreach($arr2 as $key=>$value) 
	{
		$res[$key] = $arr1[$key];
	}
	return $res;
}
?>
