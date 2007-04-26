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
		$this->result = array();
		parent::__construct(array('private' => array('format')));
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
		
		if($mod)
		{
			$mod = $requireDir.'validator.'.$mod.'.php';
			if(file_exists($mod))
			{
				$elements = array();
				require($mod);
				$result = $this->praseResult($this->format->checkFormat($this->praseElements($elements),$_POST),$elements);
			}
		}
		
		return $result;
	}
}
?>