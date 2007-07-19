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
		$elements = explode('|',$matches[0]);
		$variable = $this->praseVar(array_shift($elements));
		$result = $variable;
		
		foreach($elements as $val)
		{
			$pos = strpos($val,':');
			$method = substr($val,0,$pos);

			$parm = explode(',',substr($val,$pos+1,strlen($val) - $pos));
			
			switch($method)
			{
				case 'length':
				{
					$result = 'mgSubStr('.$result.',0,'.$parm[0].')';
					break;
				}
				case 'date':
				{
					$result = 'date("'.$parm[0].'",strtotime('.$result.'))';
					break;
				}
				default:
					break;
			}
		}
		
		return "<?php echo isset(".$variable.") ? ".$result." : NULL; ?>";
	}
	
	public function prase()
	{
		return preg_replace_callback("/\{(\\$[_0-9a-zA-Z-\ \.\'\"\(\)\,\|\:\\$]+)\}/is",array($this,'filterVarSyntaxCallback'),$this->str);
	}
}
?>
