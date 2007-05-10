<?php
/**********************************
 * Created on: 2007-2-5
 * File Name : build.tb_lang.filter.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class TbLang extends TemplateBuild
{
	private $lang;
	
	private function filterLangCallback($matches)
	{
		if(!isset($this->lang[$matches[1]]))
		{
			if(file_exists(__LANGUAGE__.'/'.$this->stack['static_var']['language'].'/lang.'.$matches[1].'.php'))
			{
				$lang = array();
				require(__LANGUAGE__.'/'.$this->stack['static_var']['language'].'/lang.'.$matches[1].'.php');
				$this->lang[$matches[1]] = $lang;
				if(isset($this->lang[$matches[1]][$matches[2]]))
				{
					return $this->lang[$matches[1]][$matches[2]];
				}
				else
				{
					return NULL;
				}
			}
			else
			{
				return NULL;
			}
		}
		else
		{
			if(isset($this->lang[$matches[1]][$matches[2]]))
			{
				return $this->lang[$matches[1]][$matches[2]];
			}
			else
			{
				return NULL;
			}
		}
	}
	
	public function prase()
	{
		return preg_replace_callback("/\{lang\.([_0-9a-zA-Z-]+)\.([_0-9a-zA-Z-]+)\}/is",array($this,'filterLangCallback'),$this->str);
	}
}
?>
