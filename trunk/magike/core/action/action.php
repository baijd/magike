<?php
/**********************************
 * Created on: 2007-2-2
 * File Name : action.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

define('E_ACTION_ACTIONNOTEXISTS','Action Is Not Exists');
define('E_ACTION_KERNELOBJECTSNOTEXISTS','Kerenl Objects Is Not Exists');
class Action extends Path
{
	function __construct($location = NULL,$message = NULL,$data = NULL)
	{
		$time = mgGetMicrotime();	//初始化解析时间
		parent::__construct($location);
		$this->stack = array();
                $this->stack[$this->moduleName] = $this->runModule();
                $this->stack[$this->moduleName]['prase_time'] = $time;  //初始化解析时间
                $this->stack[$this->moduleName]['data'] = $data;
                $this->stack[$this->moduleName]['message'] = $data;
		$this->runKernelModule();
		$this->runAction();
	}
	
	//运行核心模块
	private function runKernelModule()
	{
		$requireObjects = array();
		require(__DIR__.'/action/require_objects.php');
		$objects = mgRequireObjects(__DIR__.'/kernel','/kernel\.([_a-zA-Z0-9-]+)\.php/i');
		
		if($diff = array_diff($requireObjects,$objects))
		{
			$this->throwException(E_ACTION_KERNELOBJECTSNOTEXISTS,$diff);
		}
		else
		{
			$objects = array_merge($requireObjects,array_diff($objects,$requireObjects));
		}
		
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
		if(__DEBUG__ && !is_dir(__DIR__.'/action/'.$this->stack[$this->moduleName]['action']))
		{
			$this->throwException(E_ACTION_ACTIONNOTEXISTS,$this->stack[$this->moduleName]['action']);
		}
		else
		{
			$tmp = null;
			require_once(__DIR__.'/action/'.$this->stack[$this->moduleName]['action'].'/action.'.$this->stack[$this->moduleName]['action'].'.php');
			eval('$tmp = new '.mgFileNameToClassName($this->stack[$this->moduleName]['action'])
			.'("'.str_replace('{','{$',$this->stack[$this->moduleName]["file"]).'");');
			call_user_func(array($tmp,'runAction'));
		}
		
		$contents = ob_get_contents();
		ob_end_clean();
		echo $contents;
	}
 }
?>
