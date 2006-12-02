<?php
/**********************************
 * Created on: 2006-12-1
 * File Name : magike_exception.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

//设定异常截获函数
set_exception_handler(array('MagikeAPI','magikeExceptionHandler'));

//定义异常消息
define('E_DATABASE','Database Exception');				//数据库异常
define('E_INSTALL','Magike System Install Failed');		//安装异常
define('E_OBJECTNOTEXISTS','Object Not Exists');		//对象不存在

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
