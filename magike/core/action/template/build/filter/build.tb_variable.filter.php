<?php
/**********************************
 * Created on: 2007-2-5
 * File Name : build.tb_variable.filter.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class TbVariable extends TemplateBuild
{
	private function filterVarSyntaxCallback($matches)
	{
		$matches[0] = substr($matches[0],1,-1);
		$matches[0] = "<?php echo isset(".$matches[0].") ? ".$matches[0]." : NULL; ?>";
		return $this->praseVar($matches[0]);
	}
	
	public function prase()
	{
		return preg_replace_callback("/\{([_0-9a-zA-Z-\.\'\"\(\)\,\\$]+)\}/is",array($this,'filterVarSyntaxCallback'),$this->str);
	}
}
?>
