<?php
/**********************************
 * Created on: 2007-2-5
 * File Name : build.tb_module.filter.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

define('E_ACTION_TEMPLATEBUILD_MODULEFILENOTEXISTS','Module File Is Not Exists');

class TbModule extends TemplateBuild
{
	private $module;
	private $moduleSource;
	private $moduleFile;
	private $args;
	
	private function addCacheListenFile()
	{
		$files = array();
		require(__COMPILE__.'/'.mgPathToFileName($this->fileName).'.cnf.php');
		$files = array_merge($files,$this->moduleFile);
		mgExportArrayToFile(__COMPILE__.'/'.mgPathToFileName($this->fileName).'.cnf.php',$files,'files');
	}
	
	private function getModulePath($moduleString)
	{
		$path = explode('.',$moduleString);
		$file = 'module.'.array_pop($path).'.php';
		array_push($path,$file);
		return implode('/',$path);
	}
	
	private function getClassName($moduleString)
	{
		$path = explode('.',$moduleString);
		$className = mgFileNameToClassName(array_pop($path));
		array_push($path,$className);
		return array($className,implode('__',$path));
	}
	
	private function replaceClassName($str,$className)
	{
		return 
		preg_replace("/class {$className[0]} extends ([_0-9a-zA-Z-]+) \{\s*(.+?)\}/is","class {$className[1]} extends \\1 {\\2}",$str);
	}
	
	public function addModule($matches)
	{
		$matches[1] = str_replace(' AS ',' as ',$matches[1]);
		$moduleVar = explode(' as ',$matches[1]);
		array_walk($moduleVar,'trim');
		
		$query = explode('?',$moduleVar[0]);
		$file = __MODULE__.'/'.$this->getModulePath($query[0]);
		if(file_exists($file))
		{
			$className = $this->getClassName($query[0]);
			$nameSpace = isset($moduleVar[1]) ? $moduleVar[1] : $query[0];
			$this->module[$nameSpace] = $className[1];
			
			if(isset($query[1]))
			{
				parse_str($query[1],$this->args[$nameSpace]);
			}
			
			if(!isset($this->moduleFile[$file]))
			{
				$this->moduleFile[$file] = filemtime($file);
				$this->moduleSource .= $this->replaceClassName(php_strip_whitespace($file),$className);
			}
		}
		else
		{
			$this->throwException(E_ACTION_TEMPLATEBUILD_MODULEFILENOTEXISTS,$file);
		}
		return NULL;
	}
	
	public function prase()
	{
		$this->module = array();
		$this->args	  = array();
		$this->moduleSource = '';
		$this->moduleFile = array();
		$this->findSection('module','addModule');
		file_put_contents(__COMPILE__.'/'.mgPathToFileName($this->fileName).'.mod.php',
		"<?php\n\$module = ".var_export($this->module,true).";\n".
		"\$args = ".var_export($this->args,true).";\n?>"
		.$this->moduleSource);
		$this->addCacheListenFile();
		return $this->str;
	}
}
?>
