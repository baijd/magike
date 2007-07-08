<?php
/**********************************
 * Created on: 2007-2-5
 * File Name : build.tb_assign.filter.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class TbAssign extends TemplateBuild
{
	public function filterAssignSyntax($matches)
	{
		$finish = strtolower(str_replace('  ',' ',trim($matches[1])));
		$finish = explode(' as ',$finish);
		if(2 != count($finish))
		{
			$this->throwException(E_ACTION_TEMPLATEBUILD_ASSIGNSYNTAXERROR,$matches[1]);
		}
		else
		{
			return "<?php ".$this->praseVar($finish[1])." = ".$this->praseVar($finish[0])."; ?>";
		}
	}
	
	public function prase()
	{
		$this->findSection('assign','filterAssignSyntax');
		return $this->str;
	}
}
?>
