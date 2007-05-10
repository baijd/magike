<?php
/**********************************
 * Created on: 2007-2-4
 * File Name : template.template_build.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

//模板解析基类
class TemplateBuild extends MagikeObject
{
	private $callback;
	private $foundVar;
	public	$str;
	public	$fileName;
	public	$dirName;
	
	function __construct($str,$fileName)
	{
		$this->str = $str;
		$this->fileName = $fileName;
		$this->dirName = dirname($fileName);
		$this->foundVar = array();
		parent::__construct();
	}
	
	//找寻模板中的文件段
	protected function findSection($sectionName,$callback,$closeTag = NULL)
	{
		$this->callback = $callback;
		if(!$closeTag)
		{
			$this->str = preg_replace_callback("/\<\[{$sectionName}:\s*(.+?)\s*\]\>/is",array($this,'findSectionCallback'),$this->str);
		}
		else
		{
			$this->str = preg_replace_callback("/\<\[{$sectionName}:\s*(.+?)\s*\]\>/is",array($this,'findSectionCallback'),$this->str);
			$this->str = str_replace("<[/{$sectionName}]>",$closeTag,$this->str);		
		}
	}
	
	private function findSectionCallback($matches)
	{
		return preg_replace_callback("/\s*(.+)/is",array($this,$this->callback),$matches[1]);
	}
	
	protected function praseVar($input,&$var = array())
	{
		$this->foundVar = array();
		$input = preg_replace_callback("/\\$([_0-9a-zA-Z-\.]+)/is",array($this,'praseVarCallback'),$input);
		$var = $this->foundVar;
		return $input;
	}

	private function praseVarCallback($matches)
	{
		$keys = explode('.',$matches[1]);
		$var = "\$data['".implode("']['",$keys)."']";
		$this->foundVar[] = $var;
		return $var;
	}
	
	public function prase()
	{
		return $this->str;
	}
}
?>
