<?php
/**********************************
 * Created on: 2007-2-4
 * File Name : template.template_build.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class TemplateBuild extends ActionBuild
{
	private $callback;
	private $closeCallback;
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
	
	//寻找闭合文件段
	protected function findCloseSection($sectionName,$callback,$closeCallback,$contentCallback = NULL)
	{
		$this->callback = $callback;
		$this->closeCallback = $closeCallback;
		$this->contentCallback = $contentCallback;
		while(false !== ($pos = strpos($this->str,"<[/{$sectionName}]>")))
		{
			$out = array();
			$out[0] = $this->str;
			$out[1] = substr($this->str,0,$pos);
			$out[2] = $sectionName;
			$out[3] = substr($this->str,$pos + strlen("<[/{$sectionName}]>"),strlen($this->str) - ($pos+strlen("<[/{$sectionName}]>")));
			$this->str = call_user_func(array($this,'findCloseSectionCallback'),$out);
		}
	}
	
	public function findCloseSectionCallback($matches)
	{
		preg_match_all("/\<\[{$matches[2]}:\s*(.+?)\s*\]\>/is",$matches[1],$out);
		if(isset($out[1]))
		{
			$section = array_pop($out[1]);
			$sectionAll = array_pop($out[0]);
			$posStart = strrpos($matches[1],$sectionAll);
			$pos = $posStart + strlen($sectionAll);
			$str = substr($matches[1],$pos,strlen($matches[1]) - $pos);
			
			$matches[1] = substr($matches[1],0,$posStart).call_user_func(array($this,$this->callback),array($sectionAll,$section)).$str;

			if($this->contentCallback)
			{
				$currentPos = strrpos($matches[1],$str);
				$matches[1] = substr($matches[1],0,$currentPos).call_user_func(array($this,$this->contentCallback),$str);
			}
			$matches[2] = call_user_func(array($this,$this->closeCallback),array($sectionAll,$section));
			return $matches[1].$matches[2].$matches[3];
		}
		else
		{
			$this->throwException(E_ACTION_TEMPLATEBUILD_CANTFINDTAG,$matches[2]);
		}
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
