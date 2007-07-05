<?php
/**********************************
 * Created on: 2007-2-5
 * File Name : action_build.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

define('E_ACTION_BUILD_MODULECLASSNOTEXISTS','Module Class Is Not Exists');
define('E_ACTION_BUILD_MODULEFILENOTEXISTS','Module File Is Not Exists');

class ActionBuild extends MagikeObject
{
	public $moduleSource;
	public $extendsSoruce;
	public $moduleFile;
	public $className;
	
	function __construct()
	{
		parent::__construct();
		$this->moduleSource = '';
		$this->extendsSrouce = '';
		$this->moduleFile = array();
	}
	
	protected function getModulePath($moduleString)
	{
		$path = explode('.',$moduleString);
		$file = 'module.'.array_pop($path).'.php';
		array_push($path,$file);
		return implode('/',$path);
	}
	
	protected function getClassName($moduleString)
	{
		$path = explode('.',$moduleString);
		$className = mgFileNameToClassName(array_pop($path));
		array_push($path,$className);
		return array($className,implode('__',$path));
	}
	
	protected function replaceClassName($str,$className)
	{
		return 
		preg_replace_callback("/class ({$className[0]}) extends ([_0-9a-zA-Z-]+) \{\s*(.+?)\}/is",
		array($this,'replaceClassNameCallback'),$str);
	}
	
	public function replaceClassNameCallback($matches)
	{
		if($matches[2] == 'MagikeModule')
		{
			return "class {$this->className[1]} extends MagikeModule {{$matches[3]}}";
		}
		else
		{
			$path = explode('__',$this->className[1]);
			while(array_pop($path))
			{
				$file = __MODULE__.'/'.implode('/',$path).'/module.'.mgClassNameToFileName($matches[2]).'.php';
				$class = $this->className[1];
				$inpath = $path;
				array_push($inpath,$matches[2]);
				$inpath = implode('__',$inpath);
				
				if(isset($this->moduleFile[$file]))
				{
					return "class {$class} extends {$inpath} {{$matches[3]}}\r\n";
				}
				else if(file_exists($file))
				{
					$this->moduleFile[$file] = filemtime($file);
					$this->className = array($matches[2],$inpath);
					$this->extendsSrouce .= $this->replaceClassName(php_strip_whitespace($file),$this->className);
					return "class {$class} extends {$inpath} {{$matches[3]}}\r\n";
					break;
				}
			}
		}
		
		$this->throwException(E_ACTION_BUILD_MODULECLASSNOTEXISTS,$matches[2]);
	}
	
	public function addModule($matches)
	{
		$file = __MODULE__.'/'.$this->getModulePath($matches);
		if(file_exists($file))
		{
			$this->className = $this->getClassName($matches);
			if(!isset($this->moduleFile[$file]))
			{
				$this->moduleFile[$file] = filemtime($file);
				$this->moduleSource = $this->replaceClassName(php_strip_whitespace($file),$this->className);
				$this->moduleSource .= $this->extendsSrouce;
			}
		}
		else
		{
			$this->throwException(E_ACTION_BUILD_MODULEFILENOTEXISTS,$file);
		}
		return NULL;
	}
}
?>
