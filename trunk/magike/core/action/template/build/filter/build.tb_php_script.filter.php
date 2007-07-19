<?php
/**********************************
 * Created on: 2007-2-5
 * File Name : build.tb_php_script.filter.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class TbPhpScript extends TemplateBuild
{
	public function prase()
	{
		$this->str = str_replace('<[php]>',"<?php\r\n",$this->str);
		$this->str = str_replace('<[/php]>',"\r\n?>",$this->str);
		return $this->str;
	}
}
?>
