<?php
/**********************************
 * Created on: 2007-2-8
 * File Name : kernel.static_var.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class StaticVar extends MagikeModule
{
	private $staticVar;
	
	function __construct()
	{
		parent::__construct();
		$cache = new Cache();
		$cache->checkCacheFile(array($this->cacheFile => array('listener' => 'fileExists',
													 'callback' => array($this,'buildCache')
													)));
	}
	
	private function initStaticValue()
	{
		$staticModel = new Database();
		$this->staticVar = array();
		$staticModel->fetch(array('table' => 'table.statics'),array('function' => array($this,'pushStaticValue')));
	}
	
	public function runModule()
	{
		$staticVar = array();
		require(__CACHE__.'/static_var/static_var.php');
		$staticVar['server_timezone'] = mgGetTimeZoneDiff();
		$staticVar['blog_title'] = $staticVar['blog_name'];
		$staticVar['group'] = array(
			'administrator' => 0,
			'editor'		=> 1,
			'contributor'	=> 2,
			'visitor'		=> 3
		);
		
		if(function_exists('mb_internal_encoding'))
		{
			mb_internal_encoding($staticVar['charset']);
		}

		//add pingback header support
		header("X-Pingback:".$staticVar['index']."/xmlrpc.api");
		return $staticVar;
	}

	public function buildCache()
	{
		$this->initStaticValue();
		mgExportArrayToFile(__CACHE__.'/static_var/static_var.php',$this->staticVar,'staticVar');
	}
	
	public function pushStaticValue($val)
	{
		$this->staticVar[$val['static_name']] = $val['static_value'];
	}
}
?>
