<?php
/**********************************
 * Created on: 2007-2-5
 * File Name : build.tb_loop.filter.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class TbLoop extends TemplateBuild
{
	public function filterLoopSyntax($matches)
	{
		$finish = $this->replaceWord(str_replace('  ',' ',trim($matches[1])));
		$finish = explode(' as ',$finish);
		if(2 != count($finish))
		{
			$this->throwException(E_ACTION_TEMPLATEBUILD_LOOPSYNTAXERROR,$matches[1]);
		}
		else
		{
			$varLeft = $this->praseVar($finish[0]);
			$varExtend = NULL;
			if(false !== strpos($finish[1],"by"))
			{
				$right = explode('by',str_replace(' ','',$finish[1]));
				$varRight = $this->praseVar($right[0]);
				$varExtend = $this->praseVar($right[1]).' => ';
			}
			else
			{
				$varRight = $this->praseVar($finish[1]);
			}
			return "<?php if(isset({$varLeft}) && is_array({$varLeft})){foreach({$varLeft} as {$varExtend}{$varRight}) { ?>";
		}
	}
	
	public function filterLoopSyntaxClose($matches)
	{
		return "<?php }} ?>";
	}
	
	public function prase()
	{
		$this->findCloseSection('loop','filterLoopSyntax','filterLoopSyntaxClose');
		return $this->str;
	}
}
?>
