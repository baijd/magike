<?php
/**********************************
 * Created on: 2007-2-2
 * File Name : lib.magike_module.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

abstract class MagikeModule extends MagikeObject
{
	protected $cacheDir;
	protected $cacheFile;
	protected $getLanguage;
	protected $moduleName;
	protected $model;
	public $waittingFor;
	public $globalModel;
	
	function __construct()
	{
		parent::__construct();
		
		global $globalModel;
		$this->getLanguage = array();
		$this->waittingFor = NULL;
		$this->globalModel = &$globalModel;
	}
	
	protected function loadModel($model,$triggerException = true)
	{		
		if(isset($this->globalModel[$model]))
		{
			return $this->globalModel[$model];
		}
		else
		{
			mgTrace();
			$modelFile = __MODEL__.'/model.'.$model.'.php';
			$object = mgFileNameToClassName($model);
			if(!class_exists($object.'Model'))
			{
				if(file_exists($modelFile))
				{
					require_once($modelFile);
					if(isset($this->stack['action']['application_cache_path']))
					{
						file_put_contents($this->stack['action']['application_cache_path'],
						__DEBUG__ ? file_get_contents($this->stack['action']['application_cache_path']).mgGetScript(file_get_contents($modelFile)) : 
						file_get_contents($this->stack['action']['application_cache_path']).mgGetScript(php_strip_whitespace($modelFile)));
					}
					if(isset($this->stack['action']['application_config_path']))
					{
						$files = array();
						require($this->stack['action']['application_config_path']);
						$files[$modelFile] = filemtime($modelFile);
						mgExportArrayToFile($this->stack['action']['application_config_path'],$files,'files');
					}
				}
				else
				{
					if($triggerException)
					{
						$this->throwException(E_MODELFILENOTEXISTS,$modelFile);
					}
					else
					{
						return NULL;
					}
				}
			}
			
			$object = $object.'Model';
			$tmp = new $object();
			$this->globalModel[$model] = $tmp;
			mgTrace(false);
			mgDebug('Load Model',$tmp);
			return $tmp;
		}
	}
	
	protected function initArgs($args,$require)
	{
		$result = array();
		foreach($require as $key => $val)
		{
			if(isset($args[$key]))
			{
				$result[$key] = $args[$key];
			}
			else
			{
				$result[$key] = $val;
			}
		}
		
		return $result;
	}

	protected function onPost($key,$callback,$val = NULL)
	{
		if($val)
		{
			if(isset($_POST[$key]) && $_POST[$key] == $val)
			{
				$this->$callback();
				return true;
			}
		}
		else
		{
			if(isset($_POST[$key]))
			{
				$this->$callback();
				return true;
			}
		}
		return false;
	}
	
	protected function requireValidate()
	{
		if(isset($_SESSION['validator_key']) && isset($_SESSION['validator_val']))
		{
			$input = array();
			foreach($_POST as $key => $val)
			{
				if(in_array($key,$_SESSION['validator_key']))
				{
					$input[$key] = $val;
				}
			}
			
			if($_SESSION['validator_val'] == md5(serialize($input)))
			{
				return;
			}
			else
			{
				$this->throwException(E_FORMISOUTOFDATE);
			}
		}
		else
		{
			$this->throwException(E_FORMISOUTOFDATE);
		}
	}
	
	protected function requirePost($value = NULL,$validator = true)
	{
		if($validator)
		{
			$this->requireValidate();
		}
		
		if(NULL == $value)
		{
			if(!isset($_POST) || NULL == $_POST)
			{
				$this->throwException(E_FORMISOUTOFDATE);
			}
		}
		else
		{
			if(is_string($value))
			{
				if(!isset($_POST[$value]) || NULL == $_POST[$value])
				{
					$this->throwException(E_FORMISOUTOFDATE);
				}
			}
			else if(is_array($value))
			{
				foreach($value as $val)
				{
					if(!isset($_POST[$val]) || NULL == $_POST[$val])
					{
						$this->throwException(E_FORMISOUTOFDATE);
					}
				}
			}
		}
	}
	
	protected function requireGet($value = NULL)
	{
		if(NULL == $value)
		{
			if(!isset($_GET) || NULL == $_GET)
			{
				$this->throwException(E_FORMISOUTOFDATE);
			}
		}
		else
		{
			if(is_string($value))
			{
				if(!isset($_GET[$value]) || NULL == $_GET[$value])
				{
					$this->throwException(E_FORMISOUTOFDATE);
				}
			}
			else if(is_array($value))
			{
				foreach($value as $val)
				{
					if(!isset($_GET[$val]) || NULL == $_GET[$val])
					{
						$this->throwException(E_FORMISOUTOFDATE);
					}
				}
			}
		}
	}
	
	protected function requireAccess($access)
	{
		if($this->stack['access']['user_group'] > $this->stack['static_var']['group'][$access])
		{
			$this->throwException(E_ACCESSDENIED,$this->stack['action']['path']);
		}
	}

	protected function onGet($key,$callback,$val = NULL)
	{
		if($val)
		{
			if(isset($_GET[$key]) && $_GET[$key] == $val)
			{
				$this->$callback();
				return true;
			}
		}
		else
		{
			if(isset($_GET[$key]))
			{
				$this->$callback();
				return true;
			}
		}
		return false;
	}
	
	protected function getLanguage($key,$moduleName = null)
	{
		$moduleName = $moduleName ? $moduleName : $this->moduleName;
		if(!isset($this->getLanguage[$moduleName]) && file_exists(__LANGUAGE__.'/'.$this->stack['static_var']['language'].'/lang.'.$moduleName.'.php'))
		{
			$lang = array();
			require(__LANGUAGE__.'/'.$this->stack['static_var']['language'].'/lang.'.$moduleName.'.php');
			$this->getLanguage[$moduleName] = $lang;
		}
		else
		{
			//do nothing
		}

		if(isset($this->getLanguage[$moduleName][$key]))
		{
			return $this->getLanguage[$moduleName][$key];
		}
		else
		{
			return $key;
		}
	}
	
	protected function deleteCache($cache)
	{
		if(is_file(__CACHE__.'/'.$cache.'/'.$cache.'.php'))
		{
			unlink(__CACHE__.'/'.$cache.'/'.$cache.'.php');
			return true;
		}
		else if(is_dir(__CACHE__.'/'.$cache))
		{
			mgRmDir(__CACHE__.'/'.$cache);
			return true;
		}
		else
		{
			return false;
		}
	}
	
	//你可以自己重载这个函数作为模块的入口
	//入口函数如果有返回值,这个值将被自动压入堆栈中
	//压入的格式为$stack['module_name'] = array(...)
	//在一般情况下,请不要对堆栈直接赋值
	public function runModule($args = array())
	{
		return array();
	}
}
?>
