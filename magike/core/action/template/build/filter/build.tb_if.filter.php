<?php
/**********************************
 * Created on: 2007-2-5
 * File Name : build.tb_if.filter.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class TbIf extends TemplateBuild
{
	public function filterIfSyntaxCallback($matches)
	{
		$found = array();
		$matches[1] = $this->praseVar($matches[1],$found);
		
		foreach($found as $key => $val)
		{
			$found[$key] = "isset({$val})";
		}
		
		$setVar = implode(" && ",$found);
		if($found)
		{
			$setVar .= " && ";
		}
		
		return "<?php if({$setVar}{$matches[1]}){ ?>";
	}
	
	public function prase()
	{
		$this->findSection('if','filterIfSyntaxCallback','<?php } ?>');
		$this->str = preg_replace("/\<\[else\]\>/is","<?php }else{ ?>",$this->str);
		return $this->str;
	}
}
?>
