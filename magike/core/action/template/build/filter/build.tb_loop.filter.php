<?php
/**********************************
 * Created on: 2007-2-5
 * File Name : build.tb_loop.filter.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

define('E_ACTION_TEMPLATEBUILD_LOOPSYNTAXERROR','There Is An Template Error Near');

class TbLoop extends TemplateBuild
{
	private function filterLoopSyntax($matches)
	{
		$finish = strtolower(str_replace('  ',' ',trim($matches[1])));
		$finish = explode(' as ',$finish);
		if(2 != count($finish))
		{
			$this->throwException(E_ACTION_TEMPLATEBUILD_LOOPSYNTAXERROR,$matches[1]);
		}
		else
		{
			return "<?php foreach(".$this->praseVar($finish[0])." as "
					.$this->praseVar($finish[1]).") { ?>";
		}
	}
	
	public function prase()
	{
		$this->findSection('loop','filterLoopSyntax','<?php } ?>');
		return $this->str;
	}
}
?>
