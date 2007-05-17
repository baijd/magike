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
		parent::__construct(array('private' => array('cache')));
		$this->cache->checkCacheFile(array($this->cacheFile => array('listener' => 'fileExists',
																	 'callback' => array($this,'buildCache')
																	 )));
	}
	
	private function initStaticValue()
	{
		$this->initPublicObject(array('database'));
		$this->staticVar = array();
		$staticModel = $this->loadModel('statics');
		$staticModel->listStaticVars(array('function' => array($this,'pushStaticValue')));
	}
	
	public function runModule()
	{
		$staticVar = array();
		require($this->cacheFile);
		$staticVar['server_timezone'] = mgGetTimeZoneDiff();
		return $staticVar;
	}

	public function buildCache()
	{
		$this->initStaticValue();
		mgExportArrayToFile($this->cacheFile,$this->staticVar,'staticVar');
	}
	
	public function pushStaticValue($val)
	{
		$this->staticVar[$val['static_name']] = $val['static_value'];
	}
}
?>
