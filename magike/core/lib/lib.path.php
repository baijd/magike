<?php
/**********************************
 * Created on: 2007-2-13
 * File Name : lib.path.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

define('E_PATH_PATHNOTEXISTS','Path Is Not Exists');
class Path extends MagikeModule
{
	private $pathConfig;
	private $path;
	private $location;
	private $eregPath;
	private $value;
	private $pathCache;
	private $last;
	
	function __construct($location = NULL)
	{
		parent::__construct(array('private'=> array('cache')));
		$this->cache->checkCacheFile(array($this->cacheDir  => array('listener' => 'dirExists',
																	 'callback' => array($this,'buildCache')
																	  )));
		$this->location = $location;
	}
	
	private function loadCache()
	{
		$this->path = $this->location ? $this->location : $this->getPath();
		$this->pathConfig = array();
		$pathConfig = array();
		$pathDeep = count(explode('/',$this->path));
		$pathFile = $this->cacheDir.'/'.$pathDeep.'.php';
		$pathFileUp = $this->cacheDir.'/'.($pathDeep - 1).'.php';
		$pathFileExists = file_exists($pathFile);
		$pathFileUpExists = file_exists($pathFileUp) & $this->last;

		if($pathFileExists || $pathFileUpExists)
		{
			$ipathConfig = array();
			$ipathUpConfig = array();
			
			if($pathFileExists)
			{
				require($pathFile);
				$ipathConfig = $pathConfig;
			}
			if($pathFileUpExists)
			{
				require($pathFileUp);
				$ipathUpConfig = $pathConfig;
			}
			
			$this->pathConfig = array_merge($ipathConfig,$ipathUpConfig);
		}
		else
		{
			$this->throwException(E_PATH_PATHNOTEXISTS,$this->path);
		}
	}

	private function analysisPath()
	{
		$result = array();
		$result['path'] = $this->path;
		$found = false;
		$eregPath = '^'.$this->path.'$';		//适合正则表达的路径

		if(isset($this->pathConfig[$eregPath]))
		{
			$this->eregPath = $eregPath;
			$this->value = $this->pathConfig[$eregPath]['value'];
			$result['id'] = $this->pathConfig[$eregPath]['id'];
			$result['action'] = $this->pathConfig[$eregPath]['action'];
			$result['file'] = $this->pathConfig[$eregPath]['file'];
			$found = true;
		}
		else
		{
			foreach($this->pathConfig as $key => $val)
			{
				if(ereg($key,$this->path,$out))
				{
					$this->eregPath = $key;
					$this->value = $val['value'];
					$result['id'] = $val['id'];
					$result['action'] = $val['action'];
					$result['file'] = $val['file'];
					$found = true;
					break;
				}
			}
		}

		if(!$found)
		{
			$this->throwException(E_PATH_PATHNOTEXISTS,$this->path,'/exception' == $this->path);
		}
		
		return $result;
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
		
		$this->doRedirect($path);
		return $path;
	}

	private function getValue()
	{
		if(ereg($this->eregPath,$this->path,$out))
		{
			array_shift($out);
			foreach($out as $key => $val)
			{
				$_GET[$this->value[$key]] = $val;
			}
		}
		@reset($_GET);
	}
	
	private function doRedirect($path)
	{
		$this->last = $path[strlen($path) - 1] == '/';
		$path = $this->last ? substr($path,0,strlen($path) - 1) : $path;
		$isFile = strrpos($path,'.') === false ? false : strrpos($path,'/') < strrpos($path,'.');

		if(!$isFile & !$this->last)
		{
			$request = array_shift(explode('?',$_SERVER['REQUEST_URI'])).'/';
			if(isset($_SERVER['QUERY_STRING']) && NULL != $_SERVER['QUERY_STRING'])
			{
				$request .= '?'.$_SERVER['QUERY_STRING'];
			}
			header("location: {$request}");
		}
		if($isFile & $this->last)
		{
			$request = array_shift(explode('?',$_SERVER['REQUEST_URI']));
			$request = substr($request,0,strlen($request) - 1);
			if(isset($_SERVER['QUERY_STRING']) && NULL != $_SERVER['QUERY_STRING'])
			{
				$request .= '?'.$_SERVER['QUERY_STRING'];
			}
			header("location: {$request}");
		}
	}

	private function praseEregPath($path)
	{
		//替换常用转义字符串
		$path = str_replace(".","\.",$path);
		$path = str_replace("-","\-",$path);
		$path = str_replace("_","\_",$path);

		//替换匹配变量
		$path = preg_replace("/\[([_0-9a-zA-Z-]+)\=\%d\]/i","([0-9]+)",$path);
		$path = preg_replace("/\[([_0-9a-zA-Z-]+)\=\%s\]/i","([_0-9a-zA-Z\\x80-\\xff-]+)",$path);
		$path = preg_replace("/\[([_0-9a-zA-Z-]+)\=\%a\]/i","([_0-9a-zA-Z-]+)",$path);
		$path = '^'.$path.'[/]?$';

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
		$this->database->fectch(array('table' => 'table.paths'),array('function' => array($this,'pushPathData')));
		foreach($this->pathCache as $key => $val)
		{
			mgExportArrayToFile($this->cacheDir.'/'.$key.'.php',$val,'pathConfig');
		}
	}

	public function pushPathData($val)
	{
		$deep = count(explode("/",$val['path_name']));
		$this->pathCache[$deep][$this->praseEregPath($val['path_name'])] = array('id'  => $val['id'],
																			   	 'action' => $val['path_action'],
																			   	 'file'   => $val['path_file'],
																			   	 'value'  => $this->prasePathValue($val['path_name'])
																				 );
	}
	
	public function runModule()
	{
		$this->loadCache();
		$result = $this->analysisPath();
		$this->getValue();

		return $result;
	}
}
?>
