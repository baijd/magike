<?php
/**********************************
 * Created on: 2007-2-2
 * File Name : action.template.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

//define custom exception
define('E_ACTION_TEMPLATE_FILENOTEXISTS','Template File Is Not Exists');

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
				  __COMPILE__.'/'.mgPathToFileName($fileName).'.mod.php' 
					=> array('listener' => 'fileExists',
							 'callback' => array($this,'buildCache')),
				  __COMPILE__.'/'.mgPathToFileName($fileName).'.cnf.php' 
					=> array('listener' => 'fileDate',
							 'callback' => array($this,'buildCache'))));
		}
	}
	
	public function buildCache()
	{
		$str = file_get_contents($this->compileFile);
		require(__DIR__.'/action/template/template.template_build.php');
		$objects = array_merge(mgRequireObjects(__DIR__.'/action/template/build/core','/build\.([_a-zA-Z0-9-]+)\.core\.php/i'),
					mgRequireObjects(__DIR__.'/action/template/build/filter','/build\.([_a-zA-Z0-9-]+)\.filter\.php/i'));
		foreach($objects as $object)
		{
			$tmp = null;
			eval('$tmp = new '.$object.'($str,$this->compileFile);');
			$str = call_user_func(array($tmp,'prase'));
		}
		file_put_contents(__COMPILE__.'/'.mgPathToFileName($this->compileFile).'.php',$str);
	}
	
	public function runAction()
	{
		$module = array();	//初始化module数组
		$args	= array();	//初始化args数组
		$hasRun = array();	//已经运行的模块
		$waitting = array();

		require(__COMPILE__.'/'.mgPathToFileName($this->compileFile).'.mod.php');
		foreach($module as $object)
		{
			//创建module
			$tmp = null;
			eval('$tmp = new '.$object.'();');
			
			$waittingFor = mgFileNameToClassName($tmp->waittingFor);
			if($tmp->waittingFor && in_array($waittingFor,$module) && !in_array($waittingFor,$hasRun))
			{
				$waitting[$object] = $tmp;
				continue;
			}
			
			//运行模块入口函数runModule并将运行结果保存到临时堆栈中
			$stack = call_user_func(array($tmp,'runModule'),isset($args[$object]) ? $args[$object] : array());
			
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
			
			$hasRun[] = $object;
		}
		
		foreach($waitting as $key => $object)
		{
			//运行模块入口函数runModule并将运行结果保存到临时堆栈中
			$stack = call_user_func(array($tmp,'runModule'),isset($args[$key]) ? $args[$key] : array());
			
			//将临时堆栈中的数据转移到全局堆栈中
			//将类名转化为模块名(文件名)
			$moduleName = mgClassNameToFileName($key);
			if(isset($this->stack[$moduleName]))
			{
				$this->stack[$moduleName] = array_merge($this->stack[$moduleName],$stack);
			}
			else
			{
				$this->stack[$moduleName] = $stack;
			}
		}
		
		$this->stack['action']['prase_time'] = substr(mgGetMicrotime() - $this->stack['action']['prase_time'],0,6);

		//定义头文件
		header("content-Type: {$this->stack['static_var']['content_type']}; charset={$this->stack['static_var']['charset']}");
		$data = $this->stack;
		require(__COMPILE__.'/'.mgPathToFileName($this->compileFile).'.php');
	}
}
?>
