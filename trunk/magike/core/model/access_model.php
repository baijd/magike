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
		$this->cache->checkCacheFile(array(__CACHE__.'/system/ip.php' 	 => array('listener' => 'fileExists',
																	 	 		  'callback' => array($this,'buildIpCache'),
																	 	 		  'else'	=> array($this,'loadIpCache')
																	 	 ),
										   __CACHE__.'/system/level.php' => array('listener' => 'fileExists',
										   								 		  'callback' => array($this,'buildLevelCache'),
										   								 		  'else' => array($this,'loadLevelCache')
										   								 )));
		$this->validateIp();
		$this->validateLogin();
		$this->validateLevel();
	}

	private function validateLogin()
	{
		if(!isset($_SESSION['random']))
		{
			$_SESSION['random'] = MagikeAPI::createRandomString(7);
		}
		$this->stack->setStack('system','random',$_SESSION['random']);
		$this->stack->setStack('system','user_level',99999);			//默认分配最低权限
		$this->stack->setStack('system','login',false);

    	if(isset($_SESSION['login']) && isset($_COOKIE['auth_data']))
    	{
    		if($_SESSION['login'] == 'ok' && $_COOKIE['auth_data'] == $_SESSION['auth_data'])
		    {
		        $this->stack->setStack('system','user_level',$_SESSION['user_level']);
		        $this->stack->setStack('system','user_name',$_SESSION['user_name']);
		        $this->stack->setStack('system','user_id',$_SESSION['user_id']);
		        $this->stack->setStack('system','login',true);

		        //如果用户有活动,增加cookie时限
		        setcookie('auth_data',$_SESSION['auth_data'],time() + 3600,"/");
		    }
    	}
	}

	private function validateLevel()
	{
		if($this->stack->data['system']['user_level'] > $this->stack->data['system']['level'])
		{
			$this->throwException(E_ACCESSDENIED,$this->stack->data['system']['path'],array('MagikeAPI','errorAccessCallback'));
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
		$ip = MagikeAPI::ip2long($_SERVER["REMOTE_ADDR"]);
		$this->stack->setStack('system','ip',$_SERVER["REMOTE_ADDR"]);
		$this->stack->setStack('system','agent',$_SERVER["HTTP_USER_AGENT"]);
		$allow = true;

		foreach($this->ipConfig as $val)
		{
			if($this->isInDomain($this->stack->data['system']['domain'],$val['domain']))
			{
				$allow |= $this->checkAccess($ip,$val['action'],$val['left'],$val['right']);
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
		$currentIp['left'] = MagikeAPI::ip2long($val['ip_map_left']);
		$currentIp['right'] = MagikeAPI::ip2long($val['ip_map_right']);
		$currentIp['action'] = $val['ip_map_action'];
		$currentIp['domain'] = $val['ip_map_domain'];

		$this->ipConfig[] = $currentIp;
	}

	public function loadIpCache()
	{
		$ipConfig = array();
		require(__CACHE__.'/system/ip.php');
		$this->ipConfig = $ipConfig;
	}

	public function buildLevelCache()
	{
		$this->stack->setStackByType('level',array());
		$this->initPublicObject(array('database'));
		$this->database->fectch(array('table' => 'table.levels'),array('function' => array($this,'pushLevelData')));
		MagikeAPI::exportArrayToFile(__CACHE__.'/system/level.php',$this->stack->data['level'],'levelConfig');
	}

	public function pushLevelData($val)
	{
		$this->stack->setStack('level',$val['level_name'],$val['level_value']);
	}

	public function loadLevelCache()
	{
		$levelConfig = array();
		require(__CACHE__.'/system/level.php');
		$this->stack->setStackByType('level',$levelConfig);
	}
}
?>
