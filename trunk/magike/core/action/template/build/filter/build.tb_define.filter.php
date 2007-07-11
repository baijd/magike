<?php
/**********************************
 * Created on: 2007-2-5
 * File Name : build.tb_define.filter.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class TbDefine extends TemplateBuild
{
	private $defined;
	private $currentDefined;
	
	public function filterDefineSyntax($matches)
	{
		$defineValue = trim($matches[1]);
		$this->defined[] = $defineValue;
		return NULL;
	}
	
	public function filterIfDefinedSyntax($matches)
	{
		$this->currentDefined = false;
		if(in_array(trim($matches[1]),$this->defined))
		{
			$this->currentDefined = true;
		}
		return NULL;
	}
	
	public function filterIfDefinedContentSyntax($str)
	{
		return $this->currentDefined ? $str : NULL;
	}
	
	public function filterIfDefinedCloseSyntax($matches)
	{
		return NULL;
	}
	
	public function prase()
	{
		$this->defined = array();
		$this->findSection('define','filterDefineSyntax');
		$this->findCloseSection('ifdef','filterIfDefinedSyntax','filterIfDefinedCloseSyntax','filterIfDefinedContentSyntax');
		return $this->str;
	}
}
?>
