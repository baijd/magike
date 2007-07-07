<?php
/**********************************
 * Created on: 2007-2-2
 * File Name : lib.format.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class Format extends MagikeModule
{
	private $object;
	
	function __construct()
	{
		parent::__construct();
	}

	//检验用户提交数据格式
	public function checkFormat($fmt,$data,$object = NULL)
	{
	$error = array();
	if(($diff = array_diff(array_keys($fmt),array_keys($data))) != NULL)
	{
		foreach($diff as $val)
		{
			if(!isset($error[$val]))
			{
				$error[$val] = 'null';
			}
		}
	}

	//按提交数据检验
	foreach($data as $key => $val)
	{
		if(isset($fmt[$key]))
		{
			$now = explode(" ",$fmt[$key]);
			foreach($now as $inkey => $inval)
			{
				switch($inval)
				{
					case "null":
					{
						if(!$this->checkNull($val))
						{
							if(!isset($error[$key]))
							{
								$error[$key] = 'null';
							}
						}
						break;
					}
					case "mail":
					{
						if($this->checkNull($val) && !$this->checkMail($val))
						{
							if(!isset($error[$key]))
							{
								$error[$key] = 'mail';
							}

						}
						break;
					}
					case "num":
					{
						if($this->checkNull($val) && !$this->checkNum($val))
						{
							if(!isset($error[$key]))
							{
								$error[$key] = 'num';
							}
						}
						break;
					}
					case "url":
					{
						if($this->checkNull($val) && !$this->checkUrl($val))
						{
							if(!isset($error[$key]))
							{
								$error[$key] = 'url';
							}
						}
						break;
					}
					default:
					{
						if(ereg("^length\(([a-z0-9]+),([a-z0-9]+)\)$",$inval,$reg))
						{
							if($reg[1] == "null") $par1 = NULL;
							else	$par1 = intval($reg[1]);
							if($reg[2] == "null") $par2 = NULL;
							else	$par2 = intval($reg[2]);

							if(!$this->checkLength($val,$par1,$par2))
							{
								if(!isset($error[$key]))
								{
									$error[$key] = $inval;
								}
							}
						}
						else if(ereg("^confrm\(([_0-9a-zA-Z-]+)\)$",$inval,$reg))
						{
							if($this->checkNull($data[$reg[1]]) && !$this->checkConfrm($data[$key],$data[$reg[1]]))
							{
								if(!isset($error[$key]))
								{
									$error[$key] = $inval;
								}
							}
						}
						else if(ereg("^enum\(([,_0-9a-zA-Z-]+)\)$",$inval,$reg))
						{
							$enum = explode(",",$reg[1]);
							if(!$this->checkEnum($data[$key],$enum))
							{
								if(!isset($error[$key]))
								{
									$error[$key] = $inval;
								}
							}
						}
						if(ereg("^model\(([_0-9a-zA-Z]+),([_0-9a-zA-Z]+)\)$",$inval,$reg))
						{
							if(!$this->viaModelCheck($reg[1],$reg[2],$data[$key]))
							{
								if(!isset($error[$key]))
								{
									$error[$key] = $inval;
								}
							}
						}
						else if(ereg("^func\(([_a-z0-9A-Z-]+)\)$",$inval,$reg))
						{
							if(method_exists($object,$reg[1]))	$func = array($object,$reg[1]);
							else if(function_exists($reg[1]))	$func = $reg[1];

							$result = call_user_func($func,$val);
							if($result != NULL)
							{
								if(!isset($error[$key]))
								{
									$error[$key] = $inval;
								}
							}
						}
						break;
					}
				}
			}
		}
	}

	return $error;
	}

	//是否为空的检测
	public function checkNull($data)
	{
	if($data == NULL || $data == "") return false;
	else return true;
	}

	//电子邮件格式检测
	public function checkMail($data)
	{
	if(ereg("^[_\.0-9a-zA-Z-]+@[_0-9a-zA-Z-]+\.[\.a-zA-Z]+$",$data))	return true;
	else	return false;
	}

	//网址格式检测
	public function checkUrl($data)
	{
	if(ereg("^http://[_=&///?\.a-zA-Z0-9-]+$",$data))	return true;
	else	return false;
	}

	//是否为纯数字检测
	public function checkNum($data)
	{
	if(ereg("^[0-9]+$",$data)) 	return true;
	else	return false;
	}

	//一致性检测
	public function checkConfrm($data_one,$data_two)
	{
	if($data_one == $data_two)	return true;
	else	return false;
	}

	//长度检测
	public function checkLength($data,$min = NULL,$max = NULL)
	{
	$len = mstrlen($data);
	$rmin = true;
	$rmax = true;

	if($min != NULL)
	{
		if($len >= $min) $rmin = true;
		else $rmin = false;
	}

	if($max != NULL)
	{
		if($len <= $max) $rmax = true;
		else $rmax = false;
	}

	return $rmin & $rmax;
	}

	//枚举值检测
	public function checkEnum($data,$enum)
	{
		$enum = array_flip($enum);

		if(isset($enum[$data])) return true;
		else return false;
	}
	
	public function viaModelCheck($modelName,$methodName,$data)
	{
		$model = $this->loadModel($modelName);
		return call_user_func(array($model,$methodName),$data);
	}
}
?>
