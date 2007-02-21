<?php
/**********************************
 * Created on: 2006-12-17
 * File Name : module.admin_index.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class AdminIndex extends MagikeModule
{
	function __construct()
	{
		parent::__construct(array('public' => array('database')));
	}
	
	public function runModule()
	{
		$result = array();
		$result['server_version'] = PHP_OS." PHP ".PHP_VERSION;
		$result['magike_version'] = $this->stack['static_var']['version'];
		$result['posts_num'] 	   = $this->database->count(array('table' => 'table.posts','key' => 'id'));
		$getVersion = $this->database->fectch(array('fields' => 'VERSION() AS version'));
		$result['database_version'] = 'Mysql '.$getVersion[0]['version'];
		
		return $result;
	}
}
?>
