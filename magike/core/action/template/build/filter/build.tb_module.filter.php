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
	
	public function addModule($matches)
	{	
		$query = explode('?',$matches[1]);
		if(file_exists(__MODULE__.'/module.'.$query[0].'.php'))
		{
			$this->module[] = mgFileNameToClassName($query[0]);
			if(isset($query[1]))
			{
				parse_str($query[1],$this->args[mgFileNameToClassName($query[0])]);
			}
			$this->moduleFile[__MODULE__.'/module.'.$query[0].'.php'] = filemtime(__MODULE__.'/module.'.$query[0].'.php');
			$this->moduleSource .= __DEBUG__ ? file_get_contents(__MODULE__.'/module.'.$query[0].'.php') : 
			php_strip_whitespace(__MODULE__.'/module.'.$query[0].'.php');
		}
		else
		{
			$this->throwException(E_ACTION_TEMPLATEBUILD_MODULEFILENOTEXISTS,__MODULE__.'/module.'.$matches[1].'.php');
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
