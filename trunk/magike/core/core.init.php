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
error_reporting(E_ALL);
set_error_handler('errorHandler');

//打开会话
session_start();

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

       	if(1)
       	{
       		die(__CLASS__ . ": [{$this->code}]: {$this->message}<br />\n".$data);
       	}
       	else
       	{
       		die(new Action('/exception'));
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
	if(__LIB__.'/lib.'.mgClassNameToFileName($className).'.php')
	{
		require_once(__LIB__.'/lib.'.mgClassNameToFileName($className).'.php');
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

//关闭魔术变量功能
if (get_magic_quotes_gpc()) 
{
 	if (is_array($_GET)) 
 	{
         foreach ($_GET as $key => $val) 
         {
             if (is_array($_GET[$key])) 
             {
                 foreach ($_GET[$key] as $inkey => $inval) 
                 {
                         $_GET[$key][$inkey] = stripslashes($inval);
                 }
                 @reset($_GET[$key]);
             }
             else $_GET[$key] = stripslashes($val);
         }
         @reset($_GET);
 	}
 	
 	if (is_array($_POST)) 
 	{
         foreach ($_POST as $key => $val) 
         {
             if (is_array($_POST[$key])) 
             {
                 foreach ($_POST[$key] as $inkey => $inval) 
                 {
                         $_POST[$key][$inkey] = stripslashes($inval);
                 }
                 @reset($_POST[$key]);
             }
             else $_POST[$key] = stripslashes($val);
         }
         @reset($_POST);
 	}
 
 	if (is_array($_COOKIE)) 
 	{
         foreach ($_COOKIE as $key => $val) 
         {
             if (is_array($_COOKIE[$key])) 
             {
                 foreach ($_COOKIE[$key] as $inkey => $inval) 
                 {
                         $_COOKIE[$key][$inkey] = stripslashes($inval);
                 }
                 @reset($_COOKIE[$key]);
             }
             else $_COOKIE[$key] = stripslashes($val);
         }
         @reset($_COOKIE);
 	}
}

?>
