<?php
/**********************************
 * Created on: 2007-2-12
 * File Name : action.json_output.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class JsonOutput extends MagikeObject
{
	private $fileName;
	private $objName;
	private $path;
	
	function __construct($fileName)
	{
		parent::__construct(array('private' => array('cache')));
		$this->path = $fileName;
		$this->cache->checkCacheFile(
			array(__RUNTIME__.'/json_output/'.$fileName.'.mod.php' 
					=> array('listener' => 'fileExists',
						     'callback' => array($this,'buildCache')),
				  __RUNTIME__.'/json_output/'.$fileName.'.cnf.php' 
					=> array('listener' => 'fileDate',
						     'callback' => array($this,'buildCache'))));
		
		$this->objName = mgFileNameToUniqueClassName($fileName);
		$this->fileName =  __RUNTIME__.'/json_output/'.$fileName.'.mod.php' ;
		$this->stack['action']['application_cache_path'] = __RUNTIME__.'/json_output/'.$fileName.'.mod.php' ;
		$this->stack['action']['application_config_path'] = __RUNTIME__.'/json_output/'.$fileName.'.cnf.php' ;
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
		
		mgExportArrayToFile(__RUNTIME__.'/json_output/'.$this->path.'.cnf.php',$actionBuild->moduleFile,'files');
		file_put_contents(__RUNTIME__.'/json_output/'.$this->path.'.mod.php',$actionBuild->moduleSource.$actionBuild->extendsSoruce);
	}
	
	public function runAction()
	{
		$args = isset($_POST['args']) && is_array($_POST['args']) ? $_POST['args'] : array();
		
		require($this->fileName);
		$tmp = null;
		eval('$tmp = new '.$this->objName.'();');
		$output = call_user_func(array($tmp,'runModule'),$args);
		$this->stack['action']['content_type'] = "content-Type: text/html; charset={$this->stack['static_var']['charset']}";
		header($this->stack['action']['content_type']);
		echo Json::json_encode($output);
	}
}
?>
