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
	private $pathCache;

	function __construct()
	{
		parent::__construct(array('public' => array('stack'),
								  'private'=> array('cache')));
		$this->cache->checkCacheFile(array(__CACHE__.'/path' => array('listener' => 'dirExists',
																	 'callback' => array($this,'buildCache')
																	  )));
		$this->loadCache();
		$this->analysisPath();
		$this->getValue();
	}

	private function loadCache()
	{
		$this->path = isset($this->stack->data['system']['path']) ? $this->stack->data['system']['path'] : $this->getPath();
		$this->pathConfig = array();
		$pathConfig = array();
		$pathFile = __CACHE__.'/path/'.count(explode('/',$this->path)).'.php';

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
		$eregPath = '^'.preg_quote($this->path).'$';		//适合正则表达的路径

		if(isset($this->pathConfig[$eregPath]))
		{
			$this->eregPath = $eregPath;
			$this->value = $this->pathConfig[$eregPath]['value'];
			$this->stack->setStack('system','level',$this->pathConfig[$eregPath]['level']);
			$this->stack->setStack('system','action',$this->pathConfig[$eregPath]['action']);
			$this->stack->setStack('system','file',$this->pathConfig[$eregPath]['file']);
			$this->stack->setStack('system','domain',$this->pathConfig[$eregPath]['domain']);
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
					$this->stack->setStack('system','domain',$this->pathConfig[$eregPath]['domain']);
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
				$_GET[$this->value[$key]] = $val;
			}
		}
		@reset($_GET);
	}

	private function praseEregPath($path)
	{
		$path = preg_quote($path);

		//替换匹配变量
		$path = preg_replace("/\[([_0-9a-zA-Z-]+)\=\%d\]/i","([0-9]+)",$path);
		$path = preg_replace("/\[([_0-9a-zA-Z-]+)\=\%s\]/i","([_0-9a-zA-Z\\x80-\\xff-]+)",$path);
		$path = preg_replace("/\[([_0-9a-zA-Z-]+)\=\%a\]/i","([_0-9a-zA-Z-]+)",$path);
		$path = '^'.$path.'$';

		return $path;
	}

	private function prasePathValue($path)
	{
		$value = array();

		if(preg_match_all("/\[([_0-9a-zA-Z-]+)\=\%([d|s|a])\]/i",$path,$out))
		{
			if(isset($out[1]) && $out[1])
			{
				foreach($out[1] as $val)
				{
					$value[] = $val;
				}
			}
		}

		return $value;
	}

	public function buildCache()
	{
		$this->pathCache = array();
		$this->initPublicObject(array('database'));
		$this->database->fectch(array('table' => 'table.path'),array('function' => array($this,'pushPathData')));
		foreach($this->pathCache as $key => $val)
		{
			MagikeAPI::exportArrayToFile(__CACHE__.'/path/'.$key.'.php',$val,'pathConfig');
		}
	}

	public function pushPathData($val)
	{
		$deep = count(explode("/",$val['pt_name']));
		$this->pathCache[$deep][$this->praseEregPath($val['pt_name'])] = array('level'  => $val['pt_level'],
																			   'action' => $val['pt_action'],
																			   'file'   => $val['pt_file'],
																			   'domain'   => $val['pt_name'],
																			   'value'  => $this->prasePathValue($val['pt_name'])
																				);
	}
}
?>
