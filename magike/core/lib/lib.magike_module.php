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
	protected $cacheFile;
	protected $getLanguage;
	protected $moduleName;
	
	function __construct($args = array())
	{
		parent::__construct($args);
		$this->moduleName = mgClassNameToFileName(get_class($this));
		$this->cacheDir = __CACHE__.'/'.$this->moduleName;
		$this->cacheFile = $this->cacheDir.'/'.$this->moduleName.'.php';
		$this->getLanguage = array();
	}
	
	protected function initArgs($args,$require)
	{
		$result = array();
		foreach($require as $key => $val)
		{
			if(isset($args[$key]))
			{
				$result[$key] = $args[$key];
			}
			else
			{
				$result[$key] = $val;
			}
		}
		
		return $result;
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
	
	protected function getLanguage($key,$moduleName = null)
	{
		$moduleName = $moduleName ? $moduleName : $this->moduleName;
		if(!isset($this->getLanguage[$moduleName]) && file_exists(__LANGUAGE__.'/'.$this->stack['static_var']['language'].'/'.$moduleName.'.php'))
		{
			$lang = array();
			require(__LANGUAGE__.'/'.$this->stack['static_var']['language'].'/'.$moduleName.'.php');
			$this->getLanguage[$moduleName] = $lang;
		}
		else
		{
			//do nothing
		}

		if(isset($this->getLanguage[$moduleName][$key]))
		{
			return $this->getLanguage[$moduleName][$key];
		}
		else
		{
			return NULL;
		}
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