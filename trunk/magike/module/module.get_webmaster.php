<?php
/**********************************
 * Created on: 2007-3-4
 * File Name : module.get_webmaster.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class GetWebmaster extends MagikeModule
{
	public function runModule()
	{
		if(NULL != $this->stack['access']['user_id'])
		{
			$userModel = $this->loadModel('users');
			return $userModel->fetchOneByKey(1);
		}
		else
		{
			return array();
		}
	}
}
?>
