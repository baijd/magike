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
		$finish = strtolower(str_replace('  ',' ',trim($matches[1])));
		$finish = explode(' as ',$finish);
		if(2 != count($finish))
		{
			$this->throwException(E_ACTION_TEMPLATEBUILD_LOOPSYNTAXERROR,$matches[1]);
		}
		else
		{
			$varLeft = $this->praseVar($finish[0]);
			$varRight = $this->praseVar($finish[1]);
			return "<?php if(isset({$varRight})){\$tmp = {$varRight};} if(isset({$varLeft}) && is_array({$varLeft})){foreach({$varLeft} as {$varRight}) { ?>";
		}
	}
	
	public function filterLoopSyntaxClose($matches)
	{
		$finish = strtolower(str_replace('  ',' ',trim($matches[1])));
		$finish = explode(' as ',$finish);
		if(2 != count($finish))
		{
			$this->throwException(E_ACTION_TEMPLATEBUILD_LOOPSYNTAXERROR,$matches[1]);
		}
		else
		{
			$varLeft = $this->praseVar($finish[0]);
			$varRight = $this->praseVar($finish[1]);
			return "<?php }} if(isset(\$tmp)){{$varRight}= \$tmp;unset(\$tmp);} ?>";
		}
	}
	
	public function prase()
	{
		$this->findCloseSection('loop','filterLoopSyntax','filterLoopSyntaxClose');
		return $this->str;
	}
}
?>
