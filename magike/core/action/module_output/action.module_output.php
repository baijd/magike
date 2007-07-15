<?php
/**********************************
 * Created on: 2007-2-12
 * File Name : action.module_output.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class ModuleOutput extends MagikeObject
{
	private $fileName;
	private $objName;
	private $path;
	private $args;
	
	function __construct($fileName)
	{
		parent::__construct(array('private' => array('cache')));
		$this->args = array();
		
		$urlStr = explode('?',$fileName);
		$fileName = $urlStr[0];
		
		if(isset($urlStr[1]))
		{
			parse_str($urlStr[1],$this->args);
		}
		
		$this->path = $fileName;
		$this->cache->checkCacheFile(
			array(__RUNTIME__.'/module_output/'.$fileName.'.mod.php' 
					=> array('listener' => 'fileExists',
						     'callback' => array($this,'buildCache')),
				  __RUNTIME__.'/module_output/'.$fileName.'.cnf.php' 
					=> array('listener' => 'fileDate',
						     'callback' => array($this,'buildCache'))));
		
		$this->objName = mgFileNameToUniqueClassName($fileName);
		$this->fileName =  __RUNTIME__.'/module_output/'.$fileName.'.mod.php' ;
		$this->stack['action']['application_cache_path'] = __RUNTIME__.'/module_output/'.$fileName.'.mod.php' ;
		$this->stack['action']['application_config_path'] = __RUNTIME__.'/module_output/'.$fileName.'.cnf.php' ;
		$this->stack['action']['auto_header'] = true;
		if(!file_exists($this->fileName))
		{
			$this->throwException(E_ACTION_JSONOUTPUT_FILENOTEXISTS,$this->fileName);
		}
	}
	
	public function buildCache()
	{
		require(__DIR__.'/action/action_build.php');
		$actionBuild = new ActionBuild();
		$actionBuild->addModule($this->path);
		
		mgExportArrayToFile(__RUNTIME__.'/module_output/'.$this->path.'.cnf.php',$actionBuild->moduleFile,'files');
		file_put_contents(__RUNTIME__.'/module_output/'.$this->path.'.mod.php',$actionBuild->moduleSource.$actionBuild->extendsSoruce);
	}
	
	public function runAction()
	{
		require($this->fileName);
		$tmp = null;
		eval('$tmp = new '.$this->objName.'();');
		$output = call_user_func(array($tmp,'runModule'),$this->args);
		$this->stack['action']['content_type'] = "content-Type: {$this->stack['static_var']['content_type']}; charset={$this->stack['static_var']['charset']}";
		
		if($this->stack['action']['auto_header'])
		{
			header($this->stack['action']['content_type']);
		}
		echo $output;
	}
}
?>
