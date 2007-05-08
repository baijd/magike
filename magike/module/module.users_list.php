<?php
/**********************************
 * Created on: 2007-3-4
 * File Name : module.users_list.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class UsersList extends MagikeModule
{
	public function runModule()
	{
		$userModel = $this->loadModel('users');
		return $userModel->listUsers();
	}
}
?>
 