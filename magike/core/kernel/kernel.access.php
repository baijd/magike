<?php
/**********************************
 * Created on: 2007-2-12
 * File Name : kernel.access.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class Access extends MagikeModule
{
	private $result;

	function __construct()
	{
		parent::__construct();
		$this->result = array();
		$cache = new Cache();
		$cache->checkCacheFile(array($this->cacheFile => array('listener' => 'fileExists',
													 'callback' => array($this,'buildCache')
													)));
	}
	
	private function initUserVar()
	{
		$this->result['login'] = (isset($_COOKIE['auth_data']) && isset($_SESSION['auth_data']) 
								 && ($_COOKIE['auth_data'] == $_SESSION['auth_data'])) ? true : false;
		$this->result['user_name'] = isset($_SESSION['user_name']) && $this->result['login'] ? $_SESSION['user_name'] : NULL;
		$this->result['user_id'] = isset($_SESSION['user_id']) && $this->result['login'] ? $_SESSION['user_id'] : NULL;
		$this->result['user_group'] = isset($_SESSION['user_group']) && $this->result['login'] ? $_SESSION['user_group'] : array($this->stack['static_var']['visitor_group']);
		$this->result['auth_data'] = isset($_SESSION['auth_data']) && $this->result['login'] ? $_SESSION['auth_data'] : NULL;
	}
	
	private function checkAccess()
	{
		$access = array();
		require($this->cacheFile);
		$currentGroup = isset($access[$this->stack['action']['id']]) ? $access[$this->stack['action']['id']] : array();
		
		if(array_intersect($currentGroup,$this->result['user_group']) || '/exception' == $this->stack['action']['path'])
		{
			return;
		}
		else
		{
			$this->throwException(E_ACCESSDENIED,$this->stack['action']['path']);
		}
	}
	
	public function buildCache()
	{
		$access = array();
		$accessModel = new Database();
		$result = $accessModel->fetch(array('table' => 'table.path_group_mapping'));
		
		foreach($result as $val)
		{
			if(!isset($access[$val['path_id']]))
			{
				$access[$val['path_id']] = array();
			}
			$access[$val['path_id']][] = $val['group_id'];
		}
		
		mgExportArrayToFile($this->cacheFile,$access,'access');
	}
	
	public function runModule()
	{
		$this->initUserVar();
		$this->checkAccess();
		return $this->result;
	}
}
?>
