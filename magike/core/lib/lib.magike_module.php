<?php
/**********************************
 * Created on: 2007-2-2
 * File Name : lib.magike_module.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class MagikeModule extends MagikeObject
{
	protected $cacheDir;
	
	function __construct($args = array())
	{
		parent::__construct($args);
		$this->cacheDir = __CACHE__.'/'.mgClassNameToFileName(get_class($this));
		$this->cacheFile = $this->cacheDir.'/'.mgClassNameToFileName(get_class($this)).'.php';
	}

	protected function onPost($key,$callback,$val = NULL)
	{
		if($val)
		{
			if(isset($_POST[$key]) && $_POST[$key] == $val)
			{
				call_user_func(array($this,$callback));
				return true;
			}
		}
		else
		{
			if(isset($_POST[$key]))
			{
				call_user_func(array($this,$callback));
				return true;
			}
		}
		return false;
	}

	protected function onGet($key,$callback,$val = NULL)
	{
		if($val)
		{
			if(isset($_GET[$key]) && $_GET[$key] == $val)
			{
				call_user_func(array($this,$callback));
				return true;
			}
		}
		else
		{
			if(isset($_GET[$key]))
			{
				call_user_func(array($this,$callback));
				return true;
			}
		}
		return false;
	}
	
	//你可以自己重载这个函数作为模块的入口
	//入口函数如果有返回值,这个值将被自动压入堆栈中
	//压入的格式为$stack['module_name'] = array(...)
	//在一般情况下,请不要对堆栈直接赋值
	public function runModule($args = array())
	{
		return array();
	}
}
?>
