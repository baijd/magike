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
		if(!$this->stack->data['system']['login'])
		{
			$this->template->data['admin_login']['login_open'] = true;
			$this->onPost('do','loginAction','login');
		}
		else
		{
			$this->template->data['admin_login']['login_open'] = false;
			$this->template->data['admin_login']['message_open'] = true;
			$this->template->data['admin_login']['message'] = $this->getLanguage('login','error_open');
		}
	}

	public function loginAction()
	{
		$user = $this->database->fectch(array('table' => 'table.users',
										 	  'where' => array('template' => 'user_name = ? AND user_password = ?',
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
			$_SESSION['user_level'] = isset($this->stack->data['level'][$user[0]['user_level']]) ? $this->stack->data['level'][$user[0]['user_level']] : 99999;
			$_SESSION['user_name'] = $user[0]['user_name'];
			$_SESSION['user_id'] = $user[0]['id'];
			$_SESSION['auth_data'] = MagikeAPI::createRandomString(128);
			setcookie('auth_data',$_SESSION['auth_data'],time() + 3600,'/');
			$_SESSION['login'] = 'ok';
			header('location: '.$this->stack->data['static']['index'].'/admin');
		}
	}
}
?>
