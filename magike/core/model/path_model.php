<?php
/**********************************
 * Created on: 2006-12-4
 * File Name : path_model.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class PathModel extends MagikeObject
{
	private $pathConfig;
	private $path;
	private $eregPath;
	private $value;

	function __construct($path = NULL)
	{
		parent::__construct(array('public' => array('stack'),
								  'private'=> array('cache')));
		$this->cache->checkCacheFile(array(__CACHE__.'/path/config.php' => array('listener' => 'fileExists',
																	 'callback' => array($this,'buildCache')
																	  )));
		$this->loadCache($path);
		$this->analysisPath();
		$this->getValue();
	}

	private function loadCache($path)
	{
		$this->path = $path ? $path : $this->getPath();
		$this->pathConfig = array();
		$pathConfig = array();
		$pathFile = __CACHE__.'/path/'.count(explode('/',$this->path)).'_path.php';

		if(file_exists($pathFile))
		{
			require($pathFile);
			$this->pathConfig = $pathConfig;
		}
		else
		{
			$this->throwException(E_PATHNOTEXISTS,$this->path,array('MagikeAPI','error404Callback'));
		}
	}

	private function analysisPath()
	{
		$this->stack->setStack('system','path',$this->path);
		$found = false;
		$eregPath = '^'.quotemeta($this->path).'$';		//适合正则表达的路径

		if(isset($this->pathConfig[$eregPath]))
		{
			$this->eregPath = $eregPath;
			$this->value = $this->pathConfig[$eregPath]['value'];
			$this->stack->setStack('system','level',$this->pathConfig[$eregPath]['level']);
			$this->stack->setStack('system','action',$this->pathConfig[$eregPath]['action']);
			$this->stack->setStack('system','file',$this->pathConfig[$eregPath]['file']);
			$found = true;
		}
		else
		{
			foreach($this->pathConfig as $key => $val)
			{
				if(ereg($key,$this->path,$out))
				{
					$this->eregPath = $key;
					$this->value = $this->pathConfig[$eregPath]['value'];
					$this->stack->setStack('system','level',$this->pathConfig[$eregPath]['level']);
					$this->stack->setStack('system','action',$this->pathConfig[$eregPath]['action']);
					$this->stack->setStack('system','file',$this->pathConfig[$eregPath]['file']);
					$found = true;
					break;
				}
			}
		}

		if(!$found)
		{
			$this->throwException(E_PATHNOTEXISTS,$this->path,array('MagikeAPI','error404Callback'));
		}
	}

	private function getPath()
	{
		if(isset($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO'])
		{
			$path = $_SERVER['PATH_INFO'];
		}
		else
		{
			$path = '/';
		}

		return $path;
	}

	private function getValue()
	{
		if(ereg($this->eregPath,$this->stack->data['system']['path'],$out))
		{
			array_shift($out);
			foreach($out as $key => $val)
			{
				$this->stack->setStack('GET',$this->value[$key],$val);
			}
		}
	}
}
?>
