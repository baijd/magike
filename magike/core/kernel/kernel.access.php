<?php
/**********************************
 * Created on: 2007-2-12
 * File Name : kernel.access.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

define('E_ACCESSDENIED','Your Access Denied');
class Access extends MagikeModule
{
	private $ipConfig;
	private $result;

	function __construct()
	{
		parent::__construct(array('private' => array('cache')));
		$this->result = array();
		$this->cache->checkCacheFile(array($this->cacheDir.'/ip.php' 	 => array('listener' => 'fileExists',
																	 	 		  'callback' => array($this,'buildIpCache'),
																	 	 		  'else'	=> array($this,'loadIpCache')
																	 	 ),
										   $this->cacheDir.'/level.php' => array('listener' => 'fileExists',
										   								 		  'callback' => array($this,'buildLevelCache'),
										   								 		  'else' => array($this,'loadLevelCache')
										   								 )));
	}

	private function validateLogin()
	{
		if(!isset($_SESSION['random']))
		{
			$_SESSION['random'] = mgCreateRandomString(7);
		}
		
		$this->result['random'] = $_SESSION['random'];
		$this->result['user_level'] = 99999;
		$this->result['login'] = false;

    	if(isset($_SESSION['login']) && isset($_COOKIE['auth_data']))
    	{
    		if($_SESSION['login'] == 'ok' && $_COOKIE['auth_data'] == $_SESSION['auth_data'])
		    {
				$this->result['user_level'] = $_SESSION['user_level'];
				$this->result['user_name'] = $_SESSION['user_name'];
				$this->result['user_id'] = $_SESSION['user_id'];
				$this->result['login'] = true;

		        //如果用户有活动,增加cookie时限
		        setcookie('auth_data',$_SESSION['auth_data'],time() + 3600,"/");
		    }
    	}
	}

	private function validateLevel()
	{
		if($this->result['user_level'] > $this->stack['action']['level'])
		{
			$this->throwException(E_ACCESSDENIED);
		}
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
			case 'allow':
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

	private function validateIp()
	{
		$ip = mgIpToLong($_SERVER["REMOTE_ADDR"]);
		$this->result['ip'] = $_SERVER["REMOTE_ADDR"];
		$this->result['agent'] = $_SERVER["HTTP_USER_AGENT"];
		$allow = false;

		foreach($this->ipConfig as $val)
		{
			if($this->isInDomain($this->result['domain'],$val['domain']))
			{
				$allow |= $this->checkAccess($ip,$val['action'],$val['left'],$val['right']);
			}
		}

		if(!$allow && $this->ipConfig)
		{
			$this->throwException(E_ACCESSDENIED);
		}
	}

	public function buildIpCache()
	{
		$this->ipConfig = array();
		$this->initPublicObject(array('database'));
		$this->database->fectch(array('table' => 'table.ip_map'),array('function' => array($this,'pushIpData')));
		mgExportArrayToFile($this->cacheDir.'/ip.php',$this->ipConfig,'ipConfig');
	}

	public function pushIpData($val)
	{
		$currentIp = array();
		$currentIp['left'] = mgIpToLong($val['ip_map_left']);
		$currentIp['right'] = mgIpToLong($val['ip_map_right']);
		$currentIp['action'] = $val['ip_map_action'];
		$currentIp['domain'] = $val['ip_map_domain'];

		$this->ipConfig[] = $currentIp;
	}

	public function loadIpCache()
	{
		$ipConfig = array();
		require($this->cacheDir.'/ip.php');
		$this->ipConfig = $ipConfig;
	}

	public function buildLevelCache()
	{
		$this->result['level'] = array();
		$this->initPublicObject(array('database'));
		$this->database->fectch(array('table' => 'table.levels'),array('function' => array($this,'pushLevelData')));
		mgExportArrayToFile($this->cacheDir.'/level.php',$this->result['level'],'levelConfig');
	}

	public function pushLevelData($val)
	{
		$this->result['level'][$val['level_name']] = $val['level_value'];
	}

	public function loadLevelCache()
	{
		$levelConfig = array();
		require($this->cacheDir.'/level.php');
		$this->result['level'] = $levelConfig;
	}
	
	public function runModule()
	{
		$this->validateIp();
		$this->validateLogin();
		$this->validateLevel();
		
		return $this->result;
	}
}
?>
