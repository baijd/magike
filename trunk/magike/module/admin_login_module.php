<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : admin_login_module.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class AdminLoginModule extends MagikeModule
{
	public function runModule()
	{
		$this->template->data['admin_login']['message_open'] = false;
		$this->onPost('do','loginAction','login');
	}

	public function loginAction()
	{
		$user = $this->database->fectch(array('table' => 'table.users',
										 	  'where' => array('template' => 'u_name = ? AND u_password = ?',
										  					  'value' => array($_POST['username'],
										  					  				   md5($_POST['password'])
										  					  				  )
										  					  ),
										  	  'limit' => 1
										  	  ));
		if(NULL == $user)
		{
			$this->template->data['admin_login']['message_open'] = true;
			$this->template->data['admin_login']['message'] = $this->getLanguage('login','error');
		}
		else
		{
			header('location: '.$this->stack->data['static']['index'].'/admin');
		}
	}
}
?>
