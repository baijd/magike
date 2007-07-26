<?php
/**********************************
 * Created on: 2007-2-2
 * File Name : core.hook.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

//初始化服务器参数及状态
//设定异常截获函数以及错误截获函数
set_exception_handler('exceptionHandler');
error_reporting(__DEBUG__ ? E_ALL : E_ERROR);
if(__DEBUG__) set_error_handler('errorHandler');

//打开会话
session_start();

//定义异常
define('E_DATABASE','Database Error');
define('E_MODELFILENOTEXISTS','Model File Not Exists');
define('E_FORMISOUTOFDATE','Form Is Out Of Date');
define('E_PATH_PATHNOTEXISTS','Path Is Not Exists');
define('E_ACCESSDENIED','Access Denied');
define('E_ACTION_ACTIONNOTEXISTS','Action Is Not Exists');
define('E_ACTION_KERNELOBJECTSNOTEXISTS','Kerenl Objects Is Not Exists');
define('E_ACTION_BUILD_MODULECLASSNOTEXISTS','Module Class Is Not Exists');
define('E_ACTION_BUILD_MODULEFILENOTEXISTS','Module File Is Not Exists');
define('E_ACTION_JSONOUTPUT_FILENOTEXISTS','Json Module File Is Not Exists');
define('E_ACTION_MODULEOUTPUT_FILENOTEXISTS','Module File Is Not Exists');
define('E_ACTION_TEMPLATE_FILENOTEXISTS','Template File Is Not Exists');
define('E_ACTION_TEMPLATEBUILD_CANTFINDTAG','Cant Not Find Tag');
define('E_ACTION_TEMPLATEBUILD_INCLUDEFILENOTEXISTS','Include Template File Is Not Exists');
define('E_ACTION_TEMPLATEBUILD_ASSIGNSYNTAXERROR','There Is An Template Error Near');
define('E_ACTION_TEMPLATEBUILD_LOOPSYNTAXERROR','There Is An Template Error Near');
define('E_ACTION_TEMPLATEBUILD_MODULEFILENOTEXISTS','Module File Is Not Exists');

//自定义异常类
class MagikeException extends Exception
{
   protected $data;
   protected $callback;

   public function __construct($message, $data = NULL, $code = 0,$callback = NULL)
   {
   		parent::__construct($message, $code);
   		$this->data = $data;
   		$this->callback = $callback;
   }

   public function __toString()
   {
       	$data = '';
	
       	if(is_array($this->data))
       	{
       		foreach($this->data as $key => $val)
       		{
       			$data .= $key.':'.$val."<br />\n";
       		}
       	}
       	else
       	{
       		$data = $this->data;
       	}

       	if(__DEBUG__ || $this->code)
       	{
       		die(__CLASS__ . ": [{$this->code}]: {$this->message}<br />\n".$data);
       	}
       	else
       	{
       		new Action('/exception',$this->message,$this->data);
       		die();
       	}
   }

   final function getData()
   {
   	   	return $this->data;
   }

   final function getCallback()
   {
   	   	return $this->callback;
   }
}

//自定义异常截获函数
function exceptionHandler($exception)
{
	if($exception->getCallback())
	{
		$callback = $exception->getCallback();
		$functionExists = is_array($callback) ? method_exists($callback[0],$callback[1]) : function_exists($callback);
		if($functionExists)
		{
			call_user_func($callback,$exception->getData());
		}
		else
		{
			$exception->__toString();
		}
	}
	else
	{
		$exception->__toString();
	}
}

//自定义错误截获函数
function errorHandler($errno, $errstr, $errfile, $errline)
{
 	$errorWord = array(E_USER_ERROR 	=> 'Fatal Error type',
 					   E_USER_WARNING	=> 'Warning type',
 					   E_USER_NOTICE	=> 'Notice type',
 					   );
 	
 	if(array_key_exists($errno,$errorWord))
 	{
 		$errorWord = $errorWord[$errno];
 	}
 	else
 	{
 		$errorWord = 'Unkown error type';
 	}
 	
 	echo $errorWord."[$errno]: [file:$errfile][line:$errline] $errstr<br />\n";
}

//自定义自动加载函数
function __autoload($className)
{
	global $stack;
	$found = false;
	$fileName = strtolower(__LIB__.'/lib.'.mgClassNameToFileName($className).'.php');
	if(file_exists($fileName))
	{
		require_once($fileName);
		$found = true;
	}
	
	if(!$found)
	{
		$fileName = strtolower(__MODEL__.'/model.'.mgClassNameToFileName($className).'.php');
		if(file_exists($fileName))
		{
			require_once($fileName);
			$found = true;
		}
		
		if(isset($stack['action']['application_cache_path']) && isset($stack['action']['application_config_path']) && $found)
		{
			file_put_contents($stack['action']['application_cache_path'],file_get_contents($stack['action']['application_cache_path']).mgGetScript(php_strip_whitespace($fileName)));
			$files = array();
			require($stack['action']['application_config_path']);
			$files[$fileName] = filemtime($fileName);
			mgExportArrayToFile($stack['action']['application_config_path'],$files,'files');
		}
	}
}

//打开系统缓存
if(__GZIP__ && isset($_SERVER['HTTP_ACCEPT_ENCODING']) && ereg('gzip',$_SERVER['HTTP_ACCEPT_ENCODING']))
{
	ob_start("ob_gzhandler");
}
else
{
	ob_start();
}

//载入debug支持
if(__DEBUG__)
{
	global $debugData,$debugTime;
	
	$debugData = array();
	$debugTime = array();
	$debugTime['now'] = array();
	$debugTime['start'] = mgGetMicrotime();
	$debugTime['now'][] = $debugTime['start'];
}

//关闭魔术变量功能
if (get_magic_quotes_gpc()) 
{
	$_GET = mgStripslashesDeep($_GET);
	$_POST = mgStripslashesDeep($_POST);
	$_COOKIE = mgStripslashesDeep($_COOKIE);
	
	reset($_GET);
	reset($_POST);
	reset($_COOKIE);
}

?>
