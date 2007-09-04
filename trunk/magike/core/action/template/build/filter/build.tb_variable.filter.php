<?php
/**********************************
 * Created on: 2007-2-5
 * File Name : build.tb_variable.filter.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class TbVariable extends TemplateBuild
{
	private function myPraseVar($input)
	{
		return preg_replace_callback("/\%\\$([_0-9a-zA-Z-\.]+)\%/is",array($this,'myPraseVarCallback'),$input);
	}

	private function myPraseVarCallback($matches)
	{
		$keys = explode('.',$matches[1]);
		$var = "{\$data['".implode("']['",$keys)."']}";
		return $var;
	}
	
	private function filterVarSyntaxCallback($matches)
	{
		$matches[0] = substr($matches[0],1,-1);
		$elements = explode('|',$matches[0]);
		$variable = $this->praseVar(array_shift($elements));
		$result = $variable;
		
		foreach($elements as $val)
		{
			$pos = (strpos($val,':') === false ? strlen($val) : strpos($val,':'));
			$method = substr($val,0,$pos);

			$parm = explode(',',substr($val,$pos+1,strlen($val) - $pos));
			$parms = substr($val,$pos+1,strlen($val) - $pos);
			
			switch($method)
			{
				case 'length':
				{
					$result = 'mgSubStr('.$result.',0,'.$parm[0].')';
					break;
				}
				case 'strtodate':
				case 'date':
				{
					$result = 'date("'.$parm[0].'",strtotime('.$result.'))';
					break;
				}
				case 'inttodate':
				{
					$result = 'date("'.$parm[0].'",'.$result.')';
					break;
				}
				case 'implode':
				{
					$result = 'implode("'.$parms.'",'.$result.')';
					break;
				}
				case 'format':
				{
					$result = 'mgStringRelplace("%s",'.$result.',"'.$this->myPraseVar(addslashes($parms)).'")';
					break;
				}
				case 'md5':
				{
					$result = 'md5('.$result.')';
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
		return preg_replace_callback("/\{(\\$[_0-9a-zA-Z\x80-\xff-\ \.\'\"\(\)\,\\%\<\>\=\/\|\:\\$\\\]+)\}/is",array($this,'filterVarSyntaxCallback'),$this->str);
	}
}
?>
