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

	public static function fileToModule($fileName,$isObject = true)
	{
		return $isObject ? ucfirst(preg_replace_callback("/[\_]([a-z])/i",array('MagikeAPI','fileToModuleCallback'),$fileName))
		: preg_replace_callback("/[\_]([a-z])/i",array('MagikeAPI','fileToModuleCallback'),$fileName);
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

	public static function utf162utf8($str)
	{
        if(function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($utf16, 'UTF-8', 'UTF-16');
        }
        $bytes = (ord($utf16{0}) << 8) | ord($utf16{1});

        switch(true) {
            case ((0x7F & $bytes) == $bytes):
                return chr(0x7F & $bytes);

            case (0x07FF & $bytes) == $bytes:
                return chr(0xC0 | (($bytes >> 6) & 0x1F))
                     . chr(0x80 | ($bytes & 0x3F));

            case (0xFFFF & $bytes) == $bytes:
                return chr(0xE0 | (($bytes >> 12) & 0x0F))
                     . chr(0x80 | (($bytes >> 6) & 0x3F))
                     . chr(0x80 | ($bytes & 0x3F));
        }
        return '';
	}

    public static function utf82utf16($utf8)
    {
        if(function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($utf8, 'UTF-16', 'UTF-8');
        }

        switch(strlen($utf8)) {
            case 1:
                return $utf8;

            case 2:
                return chr(0x07 & (ord($utf8{0}) >> 2))
                     . chr((0xC0 & (ord($utf8{0}) << 6))
                         | (0x3F & ord($utf8{1})));
            case 3:
                return chr((0xF0 & (ord($utf8{0}) << 4))
                         | (0x0F & (ord($utf8{1}) >> 2)))
                     . chr((0xC0 & (ord($utf8{1}) << 6))
                         | (0x7F & ord($utf8{2})));
        }
        return '';
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

if(!function_exists('property_exists'))
{
	function property_exists($class, $property)
	{
		if (is_object($class))
		{
			$class = get_class($class);
		}
		return array_key_exists($property, get_class_vars($class));
	}
}

if(!function_exists('array_intersect_key'))
{
	function array_intersect_key($array1, $array2)
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
}

if(!function_exists('json_encode'))
{
	define('SERVICES_JSON_SLICE',1);
	define('SERVICES_JSON_IN_STR',2);
	define('SERVICES_JSON_IN_ARR',3);
	define('SERVICES_JSON_IN_OBJ',4);
	define('SERVICES_JSON_IN_CMT',5);
	define('SERVICES_JSON_LOOSE_TYPE',16);
	define('SERVICES_JSON_SUPPRESS_ERRORS',32);
	define('E_JSONTYPE','json decode error');

	function json_encode($var)
	{
        switch (gettype($var)) {
            case 'boolean':
                return $var ? 'true' : 'false';

            case 'NULL':
                return 'null';

            case 'integer':
                return (int) $var;

            case 'double':
            case 'float':
                return (float) $var;

            case 'string':
                $ascii = '';
                $strlen_var = strlen($var);

                for ($c = 0; $c < $strlen_var; ++$c) {

                    $ord_var_c = ord($var{$c});

                    switch (true) {
                        case $ord_var_c == 0x08:
                            $ascii .= '\b';
                            break;
                        case $ord_var_c == 0x09:
                            $ascii .= '\t';
                            break;
                        case $ord_var_c == 0x0A:
                            $ascii .= '\n';
                            break;
                        case $ord_var_c == 0x0C:
                            $ascii .= '\f';
                            break;
                        case $ord_var_c == 0x0D:
                            $ascii .= '\r';
                            break;

                        case $ord_var_c == 0x22:
                        case $ord_var_c == 0x2F:
                        case $ord_var_c == 0x5C:
                            $ascii .= '\\'.$var{$c};
                            break;

                        case (($ord_var_c >= 0x20) && ($ord_var_c <= 0x7F)):
                            $ascii .= $var{$c};
                            break;

                        case (($ord_var_c & 0xE0) == 0xC0):
                            $char = pack('C*', $ord_var_c, ord($var{$c + 1}));
                            $c += 1;
                            $utf16 = MagikeAPI::utf82utf16($char);
                            $ascii .= sprintf('\u%04s', bin2hex($utf16));
                            break;

                        case (($ord_var_c & 0xF0) == 0xE0):
                            $char = pack('C*', $ord_var_c,
                                         ord($var{$c + 1}),
                                         ord($var{$c + 2}));
                            $c += 2;
                            $utf16 = MagikeAPI::utf82utf16($char);
                            $ascii .= sprintf('\u%04s', bin2hex($utf16));
                            break;

                        case (($ord_var_c & 0xF8) == 0xF0):
                            $char = pack('C*', $ord_var_c,
                                         ord($var{$c + 1}),
                                         ord($var{$c + 2}),
                                         ord($var{$c + 3}));
                            $c += 3;
                            $utf16 = MagikeAPI::utf82utf16($char);
                            $ascii .= sprintf('\u%04s', bin2hex($utf16));
                            break;

                        case (($ord_var_c & 0xFC) == 0xF8):
                            $char = pack('C*', $ord_var_c,
                                         ord($var{$c + 1}),
                                         ord($var{$c + 2}),
                                         ord($var{$c + 3}),
                                         ord($var{$c + 4}));
                            $c += 4;
                            $utf16 = MagikeAPI::utf82utf16($char);
                            $ascii .= sprintf('\u%04s', bin2hex($utf16));
                            break;

                        case (($ord_var_c & 0xFE) == 0xFC):
                            $char = pack('C*', $ord_var_c,
                                         ord($var{$c + 1}),
                                         ord($var{$c + 2}),
                                         ord($var{$c + 3}),
                                         ord($var{$c + 4}),
                                         ord($var{$c + 5}));
                            $c += 5;
                            $utf16 = MagikeAPI::utf82utf16($char);
                            $ascii .= sprintf('\u%04s', bin2hex($utf16));
                            break;
                    }
                }

                return '"'.$ascii.'"';

            case 'array':
                if (is_array($var) && count($var) && (array_keys($var) !== range(0, sizeof($var) - 1))) {
                    $properties = array_map('json_name_value',
                                            array_keys($var),
                                            array_values($var));

                    foreach($properties as $property) {
                        if(json_is_error($property)) {
                            return $property;
                        }
                    }

                    return '{' . join(',', $properties) . '}';
                }

                // treat it like a regular array
                $elements = array_map('json_encode', $var);

                foreach($elements as $element) {
                    if(json_is_error($element)) {
                        return $element;
                    }
                }

                return '[' . join(',', $elements) . ']';

            case 'object':
                $vars = get_object_vars($var);

                $properties = array_map('json_name_value',
                                        array_keys($vars),
                                        array_values($vars));

                foreach($properties as $property) {
                    if(json_is_error($property)) {
                        return $property;
                    }
                }

                return '{' . join(',', $properties) . '}';

            default:
                throw new MagikeException(E_JSONTYPE,gettype($var));
        }
	}

    function json_decode($str)
    {
        $str = json_reduce_string($str);

        switch (strtolower($str)) {
            case 'true':
                return true;

            case 'false':
                return false;

            case 'null':
                return null;

            default:
                $m = array();

                if (is_numeric($str)) {
                    return ((float)$str == (integer)$str)
                        ? (integer)$str
                        : (float)$str;

                } elseif (preg_match('/^("|\').*(\1)$/s', $str, $m) && $m[1] == $m[2]) {
                    $delim = substr($str, 0, 1);
                    $chrs = substr($str, 1, -1);
                    $utf8 = '';
                    $strlen_chrs = strlen($chrs);

                    for ($c = 0; $c < $strlen_chrs; ++$c) {

                        $substr_chrs_c_2 = substr($chrs, $c, 2);
                        $ord_chrs_c = ord($chrs{$c});

                        switch (true) {
                            case $substr_chrs_c_2 == '\b':
                                $utf8 .= chr(0x08);
                                ++$c;
                                break;
                            case $substr_chrs_c_2 == '\t':
                                $utf8 .= chr(0x09);
                                ++$c;
                                break;
                            case $substr_chrs_c_2 == '\n':
                                $utf8 .= chr(0x0A);
                                ++$c;
                                break;
                            case $substr_chrs_c_2 == '\f':
                                $utf8 .= chr(0x0C);
                                ++$c;
                                break;
                            case $substr_chrs_c_2 == '\r':
                                $utf8 .= chr(0x0D);
                                ++$c;
                                break;

                            case $substr_chrs_c_2 == '\\"':
                            case $substr_chrs_c_2 == '\\\'':
                            case $substr_chrs_c_2 == '\\\\':
                            case $substr_chrs_c_2 == '\\/':
                                if (($delim == '"' && $substr_chrs_c_2 != '\\\'') ||
                                   ($delim == "'" && $substr_chrs_c_2 != '\\"')) {
                                    $utf8 .= $chrs{++$c};
                                }
                                break;

                            case preg_match('/\\\u[0-9A-F]{4}/i', substr($chrs, $c, 6)):
                                $utf16 = chr(hexdec(substr($chrs, ($c + 2), 2)))
                                       . chr(hexdec(substr($chrs, ($c + 4), 2)));
                                $utf8 .= MagikeAPI::utf162utf8($utf16);
                                $c += 5;
                                break;

                            case ($ord_chrs_c >= 0x20) && ($ord_chrs_c <= 0x7F):
                                $utf8 .= $chrs{$c};
                                break;

                            case ($ord_chrs_c & 0xE0) == 0xC0:
                                $utf8 .= substr($chrs, $c, 2);
                                ++$c;
                                break;

                            case ($ord_chrs_c & 0xF0) == 0xE0:
                                $utf8 .= substr($chrs, $c, 3);
                                $c += 2;
                                break;

                            case ($ord_chrs_c & 0xF8) == 0xF0:
                                $utf8 .= substr($chrs, $c, 4);
                                $c += 3;
                                break;

                            case ($ord_chrs_c & 0xFC) == 0xF8:
                                $utf8 .= substr($chrs, $c, 5);
                                $c += 4;
                                break;

                            case ($ord_chrs_c & 0xFE) == 0xFC:
                                $utf8 .= substr($chrs, $c, 6);
                                $c += 5;
                                break;

                        }

                    }

                    return $utf8;

                } elseif (preg_match('/^\[.*\]$/s', $str) || preg_match('/^\{.*\}$/s', $str)) {
                    if ($str{0} == '[') {
                        $stk = array(SERVICES_JSON_IN_ARR);
                        $arr = array();
                    } else {
                            $stk = array(SERVICES_JSON_IN_OBJ);
                            $obj = new stdClass();
                    }

                    array_push($stk, array('what'  => SERVICES_JSON_SLICE,
                                           'where' => 0,
                                           'delim' => false));

                    $chrs = substr($str, 1, -1);
                    $chrs = json_reduce_string($chrs);

                    if ($chrs == '') {
                        if (reset($stk) == SERVICES_JSON_IN_ARR) {
                            return $arr;

                        } else {
                            return $obj;

                        }
                    }

                    $strlen_chrs = strlen($chrs);
                    for ($c = 0; $c <= $strlen_chrs; ++$c) {

                        $top = end($stk);
                        $substr_chrs_c_2 = substr($chrs, $c, 2);

                        if (($c == $strlen_chrs) || (($chrs{$c} == ',') && ($top['what'] == SERVICES_JSON_SLICE))) {
                            $slice = substr($chrs, $top['where'], ($c - $top['where']));
                            array_push($stk, array('what' => SERVICES_JSON_SLICE, 'where' => ($c + 1), 'delim' => false));

                            if (reset($stk) == SERVICES_JSON_IN_ARR) {
                                array_push($arr, json_decode($slice));

                            } elseif (reset($stk) == SERVICES_JSON_IN_OBJ) {
                                $parts = array();
                                
                                if (preg_match('/^\s*(["\'].*[^\\\]["\'])\s*:\s*(\S.*),?$/Uis', $slice, $parts)) {
                                    $key = json_decode($parts[1]);
                                    $val = json_decode($parts[2]);
                                    $obj->$key = $val;
                                } elseif (preg_match('/^\s*(\w+)\s*:\s*(\S.*),?$/Uis', $slice, $parts)) {
                                    $key = $parts[1];
                                    $val = json_decode($parts[2]);
                                    $obj->$key = $val;
                                }

                            }

                        } elseif ((($chrs{$c} == '"') || ($chrs{$c} == "'")) && ($top['what'] != SERVICES_JSON_IN_STR)) {
                            array_push($stk, array('what' => SERVICES_JSON_IN_STR, 'where' => $c, 'delim' => $chrs{$c}));

                        } elseif (($chrs{$c} == $top['delim']) &&
                                 ($top['what'] == SERVICES_JSON_IN_STR) &&
                                 ((strlen(substr($chrs, 0, $c)) - strlen(rtrim(substr($chrs, 0, $c), '\\'))) % 2 != 1)) {
                            array_pop($stk);

                        } elseif (($chrs{$c} == '[') &&
                                 in_array($top['what'], array(SERVICES_JSON_SLICE, SERVICES_JSON_IN_ARR, SERVICES_JSON_IN_OBJ))) {
                            array_push($stk, array('what' => SERVICES_JSON_IN_ARR, 'where' => $c, 'delim' => false));
                        } elseif (($chrs{$c} == ']') && ($top['what'] == SERVICES_JSON_IN_ARR)) {
                            array_pop($stk);
                        } elseif (($chrs{$c} == '{') &&
                                 in_array($top['what'], array(SERVICES_JSON_SLICE, SERVICES_JSON_IN_ARR, SERVICES_JSON_IN_OBJ))) {
                            array_push($stk, array('what' => SERVICES_JSON_IN_OBJ, 'where' => $c, 'delim' => false));
                        } elseif (($chrs{$c} == '}') && ($top['what'] == SERVICES_JSON_IN_OBJ)) {
                            array_pop($stk);
                        } elseif (($substr_chrs_c_2 == '/*') &&
                                 in_array($top['what'], array(SERVICES_JSON_SLICE, SERVICES_JSON_IN_ARR, SERVICES_JSON_IN_OBJ))) {
                            array_push($stk, array('what' => SERVICES_JSON_IN_CMT, 'where' => $c, 'delim' => false));
                            $c++;
                        } elseif (($substr_chrs_c_2 == '*/') && ($top['what'] == SERVICES_JSON_IN_CMT)) {
                            array_pop($stk);
                            $c++;
                            for ($i = $top['where']; $i <= $c; ++$i)
                                $chrs = substr_replace($chrs, ' ', $i, 1);
                        }

                    }

                    if (reset($stk) == SERVICES_JSON_IN_ARR) {
                        return $arr;

                    } elseif (reset($stk) == SERVICES_JSON_IN_OBJ) {
                        return $obj;

                    }

                }
        }
    }

    function json_is_error($data, $code = null)
    {
		if (is_object($data) && (get_class($data) == 'services_json_error' ||
                                 is_subclass_of($data, 'services_json_error'))) {
            return true;
        }

        return false;
    }

    function json_name_value($name, $value)
    {
        $encoded_value = json_encode($value);

        if(json_is_error($encoded_value)) {
            return $encoded_value;
        }

        return json_encode(strval($name)) . ':' . $encoded_value;
    }

    function json_reduce_string($str)
    {
        $str = preg_replace(array(
                '#^\s*//(.+)$#m',
                '#^\s*/\*(.+)\*/#Us',
                '#/\*(.+)\*/\s*$#Us'
            ), '', $str);
        return trim($str);
    }
}
?>
