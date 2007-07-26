<?php
/**********************************
 * Created on: 2007-2-5
 * File Name : build.tb_module.filter.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class TbModule extends TemplateBuild
{
	private $module;
	public $moduleSource;
	public $extendsSoruce;
	public $moduleFile;
	public $className;
	private $args;
	
	private function addCacheListenFile()
	{
		$files = array();
		require(__RUNTIME__.'/template/'.mgPathToFileName($this->fileName).'.cnf.php');
		$files = array_merge($files,$this->moduleFile);
		mgExportArrayToFile(__RUNTIME__.'/template/'.mgPathToFileName($this->fileName).'.cnf.php',$files,'files');
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
			$this->className = $this->getClassName($query[0]);
			$nameSpace = isset($moduleVar[1]) ? $moduleVar[1] : $query[0];
			if(isset($this->module[$nameSpace]))
			{
				$this->throwException(E_ACTION_BUILD_NAMESPACEBEENUSED,$nameSpace);
			}
			$this->module[$nameSpace] = $this->className[1];
			
			if(isset($query[1]))
			{
				parse_str($query[1],$this->args[$nameSpace]);
			}
			
			if(!isset($this->moduleFile[$file]))
			{
				$this->moduleFile[$file] = filemtime($file);
				$this->moduleSource .= $this->replaceClassName(mgGetScript(php_strip_whitespace($file)),$this->className);
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
		$this->extendsSrouce = '';
		$this->moduleFile = array();
		$this->findSection('module','addModule');
		file_put_contents(__RUNTIME__.'/template/'.mgPathToFileName($this->fileName).'.mod.php',
		"<?php\n\$module = ".var_export($this->module,true).";\n".
		"\$args = ".var_export($this->args,true).";\n?>"
		.$this->moduleSource.$this->extendsSrouce);
		$this->addCacheListenFile();
		return $this->str;
	}
}
?>
