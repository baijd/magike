<?php
/**********************************
 * Created on: 2007-2-2
 * File Name : action.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

define('E_ACTION_ACTIONNOTEXISTS','Action Is Not Exists');
class Action extends MagikeObject
{
	function __construct()
	{
		parent::__construct();
		$this->runKernelModule();
		$this->runAction();
	}
	
	//运行核心模块
	private function runKernelModule()
	{
		$objects = mgRequireObjects(__DIR__.'/kernel','/kernel\.([_a-zA-Z0-9-]+)\.php/i');
		foreach($objects as $object)
		{
			//创建核心模块
			$tmp = null;
			eval('$tmp = new '.$object.'();');
			//运行入口函数
			$stack = call_user_func(array($tmp,'runModule'));
			
			//将临时堆栈中的数据转移到全局堆栈中
			//将类名转化为模块名(文件名)
			$moduleName = mgClassNameToFileName($object);
			if(isset($this->stack[$moduleName]))
			{
				$this->stack[$moduleName] = array_merge($this->stack[$moduleName],$stack);
			}
			else
			{
				$this->stack[$moduleName] = $stack;
			}
		}
	}
	
	//执行动作
	private function runAction()
	{
		//分析path模块传递的数据
		if(!is_dir(__DIR__.'/action/'.$this->stack['path']['action']))
		{
			$this->throwException(E_ACTION_TEMPLATE_FILENOTEXISTS,$this->stack['path']['action']);
		}
		else
		{
			$tmp = null;
			require(__DIR__.'/action/'.$this->stack['path']['action'].'/action.'.$this->stack['path']['action'].'.php');
			eval('$tmp = new '.mgFileNameToClassName($this->stack['path']['action'])
			.'(mgReplaceVar($this->stack["path"]["file"],$this->stack["static_var"]));');
			call_user_func(array($tmp,'runAction'));
		}
		
		$contents = ob_get_contents();
		ob_end_clean();
		echo $contents;
	}
 }
 ?>
