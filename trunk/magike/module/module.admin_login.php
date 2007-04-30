<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.admin_login.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class AdminLogin extends MagikeModule
{
	private $result;
	
	function __construct()
	{
		parent::__construct(array('public' => array('database')));
		$this->result = array();
	}
	
	public function runModule()
	{
		$this->result['message_open'] = false;
		if(!$this->stack['access']['login'])
		{
			$this->onPost('do','loginAction','login');
		}
		else
		{
			$this->result['message_open'] = true;
			$this->result['message'] = $this->getLanguage('login','error_open');
		}
		return $this->result;
	}

	public function loginAction()
	{
		$user = $this->database->fectchOne(array('table' => 'table.users',
										 	  'where' => array('template' => 'user_name = ? AND user_password = ?',
										  					   'value' => array($_POST['username'],
										  					  				   md5($_POST['password'])
										  					  				  )
										  					   ),
										  	  'limit' => 1
										  	  ));
		if(NULL == $user)
		{
			$this->result['message_open'] = true;
			$this->result['message'] = $this->getLanguage('error','login');
		}
		else
		{
			$group = $this->database->fectch(array('table' => 'table.user_group_mapping',
												   'where' => array('template' => 'user_id = ?',
												   					'value'	   => array($user['id']))
													));
			$userGroup = array();
			foreach($group as $val)
			{
				$userGroup[] = $val['group_id'];
			}
			
			$_SESSION['user_name'] = $user['user_name'];
			$_SESSION['user_id'] = $user['id'];
			$_SESSION['user_group'] = $userGroup;
			$_SESSION['auth_data'] = mgCreateRandomString(128);
			
			setcookie('auth_data',$_SESSION['auth_data'],time() + 3600,'/');
			header('location: '.$this->stack['static_var']['index'].'/admin');
		}
	}
}
?>