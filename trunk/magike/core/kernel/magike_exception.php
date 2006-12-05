<?php
/**********************************
 * Created on: 2006-12-1
 * File Name : magike_exception.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

//设定异常截获函数以及错误截获函数
set_exception_handler(array('MagikeAPI','exceptionHandler'));
error_reporting(E_ALL);
set_error_handler(array('MagikeAPI','errorHandler'));
//打开会话
session_start();

//定义异常消息
define('E_DATABASE','Database Exception');				//数据库异常
define('E_INSTALL','Magike System Install Failed');		//安装异常
define('E_OBJECTNOTEXISTS','Object Not Exists');		//对象不存在
define('E_FILENOTEXISTS','File Not Exists');			//文件不存在
define('E_PATHNOTEXISTS','Path Not Exists');			//路径不存在
define('E_ACCESSDENIED','Access Denied');				//权限被禁止

class MagikeException extends Exception
{
   protected $data;
   protected $callback;

   public function __construct($message, $data = NULL, $callback = NULL, $code = 0)
   {
   		parent::__construct($message, $code);
   		$this->data = $data;
   		$this->callback = $callback;
   }

   public function __toString()
   {
       	return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
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
?>
