<?php
/**********************************
 * Created on: 2007-2-5
 * File Name : build.tb_if.filter.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class TbIf extends TemplateBuild
{
	private function filterIfSyntaxCallback($matches)
	{
		$matches[1] = $this->praseVar($matches[1]);
		return "<?php if($matches[1]){ ?>";
	}
	
	public function prase()
	{
		$this->findSection('if','filterIfSyntaxCallback','<?php } ?>');
		return $this->str;
	}
}
?>
