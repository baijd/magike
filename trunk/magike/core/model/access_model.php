<?php
/**********************************
 * Created on: 2006-12-5
 * File Name : access_model.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class AccessModel extends MagikeObject
{
	private $ipConfig;

	function __construct()
	{
		parent::__construct(array('public'  => array('stack'),
								  'private' => array('cache')));
		$this->cache->checkCacheFile(array(__CACHE__.'/system/ip.php' => array('listener' => 'fileExists',
																	 'callback' => array($this,'buildIpCache'),
																	 'else'	=> array($this,'loadIpCache')
																	 )));
		$this->validataIp();
	}

	private function isInDomain($idomain,$domain)
	{
		if(0 === strpos($idomain,$domain))
		{
			$str = str_replace($idomain,'',$domain);
			if(NULL == $str)
			{
				return true;
			}
			else
			{
				if('/' == $str[0])
				{
					return true;
				}
				else
				{
					return false;
				}
			}
		}
		else
		{
			return false;
		}
	}


	private function checkAccess($ip,$action,$left,$right)
	{
		switch($action)
		{
			case 'deny':
			{
				if($ip >= $left && $ip <= $right)
				{
					return false;
				}
				else
				{
					return true;
				}
				break;
			}
			case 'deny except':
			{
				if($ip >= $left && $ip <= $right)
				{
					return true;
				}
				else
				{
					return false;
				}
				break;
			}
			default:
				return false;
				break;
		}
	}

	private function validataIp()
	{
		$ip = MagikeAPI::ip2long($_SERVER["REMOTE_ADDR"]);
		$this->stack->setStack('system','ip',$_SERVER["REMOTE_ADDR"]);
		$this->stack->setStack('system','agent',$_SERVER["HTTP_USER_AGENT"]);
		$allow = true;

		foreach($this->ipConfig as $val)
		{
			if($this->isInDomain($this->stack->data['system']['domain'],$val['domain']))
			{
				$allow &= $this->checkAccess($ip,$val['action'],$val['left'],$val['right']);
			}
		}

		if(!$allow)
		{
			$this->throwException(E_ACCESSDENIED,$this->stack->data['system']['path'],array('MagikeAPI','errorAccessCallback'));
		}
	}

	public function buildIpCache()
	{
		$this->ipConfig = array();
		$this->initPublicObject(array('database'));
		$this->database->fectch(array('table' => 'table.ip_map'),array('function' => array($this,'pushIpData')));
		MagikeAPI::exportArrayToFile(__CACHE__.'/system/ip.php',$this->ipConfig,'ipConfig');
	}

	public function pushIpData($val)
	{
		$currentIp = array();
		$currentIp['left'] = MagikeAPI::ip2long($val['im_left']);
		$currentIp['right'] = MagikeAPI::ip2long($val['im_right']);
		$currentIp['action'] = $val['im_action'];
		$currentIp['domain'] = $val['im_domain'];

		$this->ipConfig[] = $currentIp;
	}

	public function loadIpCache()
	{
		$ipConfig = array();
		require(__CACHE__.'/system/ip.php');
		$this->ipConfig = $ipConfig;
	}
}
?>
