<?php
/**********************************
 * Created on: 2006-11-30
 * File Name : exception_model.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class ExceptionModel extends MagikeObject
{
	function __construct()
	{
		set_exception_handler(array($this, 'exceptionHandler'));
	}

	public function exceptionHandler($exception)
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
}

//扩展的异常处理类
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
