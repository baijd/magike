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
		$this->stack[$this->moduleName]['message'] = $message;
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
			
			mgTrace();
			eval('$tmp = new '.$object.'();');
			mgTrace(false);
			mgDebug('Create Kernel Module',$tmp);
			
			//运行入口函数
			mgTrace();
			$stack = call_user_func(array($tmp,'runModule'));
			mgTrace(false);
			mgDebug('Run Kernel Module',$tmp);
			
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
		$cacheTime = intval($this->stack[$this->moduleName]['cache']);
		$pastTime = false;
		if($cacheTime)
		{
			$time = time();
			$fileName = md5($_SERVER['REQUEST_URI']);
			$path = __HTML_CACHE__.mgGetGuidPath($fileName);
			$filePath = $path.'/'.$fileName;
			$mimePath = $path.'/'.$fileName.'.mime';
			if(!file_exists($filePath) || !file_exists($mimePath))
			{
				$pastTime = true;
			}
			else
			{
				$pastTime = $cacheTime > ($time - filemtime($filePath)) ? false : true;
			}
		}
		else
		{
			//do nothing
		}
		
		if(!$cacheTime || $pastTime || __DEBUG__)
		{
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
			
			if($cacheTime)
			{
				if(!is_dir($path))
				{
					mgMkdir($path);
				}
				file_put_contents($mimePath,$this->stack[$this->moduleName]['content_type']);
				file_put_contents($filePath,$contents);
			}
			
			ob_end_clean();
			echo $contents;
		}
		else
		{
			header(file_get_contents($mimePath));
			echo file_get_contents($filePath);
		}
		
		if(__DEBUG__)
		{
			global $debugData;
			mgPrintDebug(__DEBUG_LOG__,$debugData);
		}
	}
 }
?>
