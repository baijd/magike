<?php
/**********************************
 * Created on: 2007-2-2
 * File Name : action.template.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class Template extends MagikeObject
{
	private $compileFile;
	
	function __construct($fileName)
	{
		parent::__construct(array('private' => array('cache')));
		$fileName = __TEMPLATE__.$fileName;
		if(__DEBUG__ && !file_exists($fileName))
		{
			$this->throwException(E_ACTION_TEMPLATE_FILENOTEXISTS,$fileName,
								  '/exception' == $this->stack['action']['path']);
		}
		else
		{
			$this->compileFile = $fileName;
			$this->cache->checkCacheFile(
			array(__COMPILE__.'/'.mgPathToFileName($fileName).'.php' 
					=> array('listener' => 'fileExists',
							 'callback' => array($this,'buildCache')),
				  __RUNTIME__.'/template/'.mgPathToFileName($fileName).'.mod.php' 
					=> array('listener' => 'fileExists',
							 'callback' => array($this,'buildCache')),
				  __RUNTIME__.'/template/'.mgPathToFileName($fileName).'.cnf.php' 
					=> array('listener' => 'fileDate',
							 'callback' => array($this,'buildCache'))));
		}
	}
	
	private function pushData($name,$data,$stack)
	{
		$head = $name[0];
		if(isset($name[1]))
		{
			array_shift($name);
			if(!isset($stack[$head]))
			{
				$stack[$head]  = array();
			}
			$stack[$head] = $this->pushData($name,$data,$stack[$head]);
			return $stack;
		}
		else
		{
			$stack[$head] = $data;
			return $stack;
		}
	}
	
	public function buildCache()
	{
		$str = file_get_contents($this->compileFile);
		require(__DIR__.'/action/action_build.php');
		require(__DIR__.'/action/template/template.template_build.php');
		$objects = array_merge(mgRequireObjects(__DIR__.'/action/template/build/core','/build\.([_a-zA-Z0-9-]+)\.core\.php/i'),
					mgRequireObjects(__DIR__.'/action/template/build/filter','/build\.([_a-zA-Z0-9-]+)\.filter\.php/i'));
		foreach($objects as $object)
		{
			$tmp = null;
			eval('$tmp = new '.$object.'($str,$this->compileFile);');
			$str = call_user_func(array($tmp,'prase'));
		}
		
		if(!is_dir(__COMPILE__))
		{
			mgMkdir(__COMPILE__);
		}
		file_put_contents(__COMPILE__.'/'.mgPathToFileName($this->compileFile).'.php',$str);
	}
	
	public function runAction()
	{
		$module = array();	//初始化module数组
		$args	= array();		//初始化args数组
		$hasRun = array();	//已经运行的模块
		$waitting = array();	//正在等待的模块
		
		$this->stack['action']['application_cache_path'] = __RUNTIME__.'/template/'.mgPathToFileName($this->compileFile).'.mod.php';
		$this->stack['action']['application_config_path'] = __RUNTIME__.'/template/'.mgPathToFileName($this->compileFile).'.cnf.php';
		require($this->stack['action']['application_cache_path']);
		
		foreach($module as $nameSpace => $object)
		{
			//创建module
			$tmp = null;
			mgTrace();
			eval('$tmp = new '.$object.'();');
			mgTrace(false);
			mgDebug('Create Module',$tmp);
			
			if($tmp->waittingFor && isset($module[$tmp->waittingFor]) && !isset($hasRun[$tmp->waittingFor]))
			{
				$waitting[$nameSpace] = $tmp;
				continue;
			}
			
			//运行模块入口函数runModule并将运行结果保存到临时堆栈中
			mgTrace();
			$stack = call_user_func(array($tmp,'runModule'),isset($args[$nameSpace]) ? $args[$nameSpace] : array());
			mgTrace(false);
			mgDebug('Run Module',$tmp);
			
			//将临时堆栈中的数据转移到全局堆栈中
			$this->stack = $this->pushData(explode('.',$nameSpace),$stack,$this->stack);
			
			$hasRun[$nameSpace] = $object;
		}
		
		foreach($waitting as $key => $object)
		{
			//运行模块入口函数runModule并将运行结果保存到临时堆栈中
			mgTrace();
			$stack = call_user_func(array($object,'runModule'),isset($args[$key]) ? $args[$key] : array());
			mgTrace(false);
			mgDebug('Run Module',$tmp);
			
			//将临时堆栈中的数据转移到全局堆栈中
			$this->stack = $this->pushData(explode('.',$key),$stack,$this->stack);
		}
		
		$this->stack['action']['prase_time'] = substr(mgGetMicrotime() - $this->stack['action']['prase_time'],0,6);
		$this->stack['action']['content_type'] = "content-Type: {$this->stack['static_var']['content_type']}; charset={$this->stack['static_var']['charset']}";

		//定义头文件
		header($this->stack['action']['content_type']);
		$data = $this->stack;
		require(__COMPILE__.'/'.mgPathToFileName($this->compileFile).'.php');
	}
}
?>
