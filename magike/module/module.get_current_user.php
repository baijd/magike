<?php
/**********************************
 * Created on: 2007-3-4
 * File Name : module.get_current_user.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class GetCurrentUser extends MagikeModule
{
	function __construct()
	{
		parent::__construct(array('public' => array('database')));
	}
	
	public function runModule()
	{
		if(NULL != $this->stack['access']['user_id'])
		{
			$now = $this->database->fectch(array('table' => 'table.users',
												 'where' => array(
												 		 'template' => 'id = ?',
												 		 'value'	=> array($this->stack['access']['user_id'])
												 )));
												 
			return $now ? $now[0] : array();
		}
		else
		{
			return array();
		}
	}
}
?>
