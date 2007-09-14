<?php
/**********************************
 * Created on: 2007-4-14
 * File Name : module.validator.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class Validator extends MagikeModule
{
	private $result;
	
	function __construct()
	{
		parent::__construct();
		$this->result = array();
	}
	
	private function praseElements($elements)
	{
		$result = array();
		foreach($elements as $key => $val)
		{
			$result[$key] = implode(" ",array_keys($val));
		}
		return $result;
	}
	
	private function praseResult($result,$elements)
	{
		if($result)
		{
			$word = array();
			foreach($result as $key => $val)
			{
				if(isset($elements[$key][$val]))
				{
					$word[$key] = $elements[$key][$val];
				}
			}
			return $word;
		}
		else
		{
			return 0;
		}
	}
	
	public function runModule()
	{
		$mod = isset($_GET['mod']) ? $_GET['mod'] : NULL;
		$requireDir = __MODULE__.'/'.$this->moduleName.'/';
		$result = 0;
		
		//fix jquery 1.2.0 Bug
		if(isset($_POST['mod']))
		{
			unset($_POST['mod']);
			reset($_POST);
		}
		
		$input = $_POST;
		$_SESSION['validator_val'] = md5(serialize($input));
		$_SESSION['validator_key'] = array_keys($input);
		
		$formatValidator = new Format();
		
		if($mod)
		{
			$mod = $requireDir.'validator.'.$mod.'.php';
			if(file_exists($mod))
			{
				$elements = array();
				require($mod);
				$result = $this->praseResult($formatValidator->checkFormat($this->praseElements($elements),$_POST),$elements);
			}
		}
		
		return $result;
	}
}
?>