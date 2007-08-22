<?php
/**********************************
 * Created on: 2007-2-2
 * File Name : action.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

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
		
		foreach($requireObjects as $file => $object)
		{
			require_once(__DIR__.'/kernel/'.$file);
			//创建核心模块
			
			mgTrace();
			$tmp = new $object();
			mgTrace(false);
			mgDebug('Create Kernel Module',$tmp);
			
			//运行入口函数
			$moduleName = mgClassNameToFileName($object);
			mgTrace();
			$this->stack[$moduleName] = $tmp->runModule();
			mgTrace(false);
			mgDebug('Run Kernel Module',$tmp);
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
				$tmp->runAction();
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
