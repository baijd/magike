<?php
/**********************************
 * Created on: 2007-2-5
 * File Name : build.tb_constant.filter.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class TbConstant extends TemplateBuild
{
	private function filterVarSyntaxCallback($matches)
	{
		$matches[1] = substr($matches[1],1);
		$matches[0] = "<?php echo defined('".$matches[1]."') ? ".$matches[1]." : NULL; ?>";
		return $matches[0];
	}
	
	public function prase()
	{
		return preg_replace_callback("/\{(\![_0-9a-zA-Z-]+)\}/is",array($this,'filterVarSyntaxCallback'),$this->str);
	}
}
?>
