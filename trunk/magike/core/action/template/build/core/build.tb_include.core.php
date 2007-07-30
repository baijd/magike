<?php
/**********************************
 * Created on: 2007-2-5
 * File Name : build.tb_include.core.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

//处理include语法
class TbInclude extends TemplateBuild
{
	private $found;
	private $include;
	
	private function praseLinkPath($fileString,$dirName)
	{
		return preg_replace("/\<\[include:\s*(.+?)\s*\]\>/is","<[include:".$dirName."/\\1]>",$fileString);
	}
	
	private function clearBOM($contents) 
	{
		return strlen($contents) >= 3 && (ord(substr($contents,0,1))==239 && ord(substr($contents,1,1))==187 && ord(substr($contents,2,1))==191) ?
		substr($contents, 3) : $contents;
	}

	
	public function linkInclude($matches)
	{
		$this->found = true;
		$includeFile = $this->dirName.'/'.$matches[1].'.tpl';
		$dirName = str_replace($this->dirName,'',dirname($includeFile));
		if(!file_exists($includeFile))
		{
			$this->throwException(E_ACTION_TEMPLATEBUILD_INCLUDEFILENOTEXISTS,$includeFile);
		}
		else
		{
			$this->include[$includeFile] = filemtime($includeFile);
			return $this->praseLinkPath($this->clearBOM(file_get_contents($includeFile)),$dirName);
		}
	}
	
	public function prase()
	{
		$this->include = array();
		$this->include[$this->fileName] = filemtime($this->fileName);
		
		//循环连接所有文件
		do
		{
			$this->found = false;
			$this->findSection('include','linkInclude');
		}while($this->found);
		
		//生成配置文件
		mgExportArrayToFile(__RUNTIME__.'/template/'.mgPathToFileName($this->fileName).'.cnf.php',$this->include,'files');
		return $this->clearBOM($this->str);
	}
}
?>
