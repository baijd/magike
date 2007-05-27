<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.admin_logout.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class AdminLogout extends MagikeModule
{
	public function runModule()
	{
    	session_unregister('user_name');
    	session_unregister('user_id');
    	session_unregister('user_group');
    	session_unregister('auth_data');
    	setcookie('auth_data','');

    	header('location: '.$this->stack['static_var']['siteurl']);
    	return NULL;
	}
}
?>
