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
	public	$str;
	public	$fileName;
	public	$dirName;
	
	function __construct($str,$fileName = NULL)
	{
		$this->str = $str;
		$this->fileName = $fileName;
		$this->dirName = dirname($fileName);
		parent::__construct();
	}
	
	//找寻模板中的文件段
	protected function findSection($sectionName,$callback,$closeTag = NULL)
	{
		$this->callback = $callback;
		if(!$closeTag)
		{
			$this->str = preg_replace_callback("/\<section:{$sectionName}\s*(.+?)\s*\/\>/is",array($this,'findSectionCallback'),$this->str);
		}
		else
		{
			$this->str = preg_replace_callback("/\<section:{$sectionName}\s*(.+?)\s*\>/is",array($this,'findSectionCallback'),$this->str);
			$this->str = str_replace("</section:{$sectionName}>",$closeTag,$this->str);		
		}
	}
	
	private function findSectionCallback($matches)
	{
		return preg_replace_callback("/\s*content\s*=\s*\"s*(.+?)\"/is",array($this,$this->callback),$matches[1]);
	}
	
	protected function praseVar($input)
	{
		$input = preg_replace_callback("/\\$([_0-9a-zA-Z-\.]+)/is",array($this,'praseVarCallback'),$input);
		return $input;
	}

	private function praseVarCallback($matches)
	{
		$keys = explode('.',$matches[1]);
		return "\$this->stack['".implode("']['",$keys)."']";
	}
	
	public function prase()
	{
		return $this->str;
	}
}
?>
