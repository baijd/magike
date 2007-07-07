<?php
/**********************************
 * Created on: 2007-3-6
 * File Name : module.register.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class Register extends MagikeModule
{
	private $result;
	public function insertUser()
	{
		$this->requirePost(NULL,false);
		$userModel = $this->loadModel('users');
		
		$password = isset($_POST['user_password']) && $_POST['user_password'] ? $_POST['user_password'] : mgCreateRandomString(7);
		$insertId = 
		$userModel->insertTable(array('user_name' => $_POST['user_name'],
									  'user_password'	=> md5($password),
									  'user_mail'	=> $_POST['user_mail'],
									  'user_url'	=> isset($_POST['user_url']) ? $_POST['user_url'] : NULL,
									  'user_about'	=> isset($_POST['user_about']) ? $_POST['user_about'] : NULL));
		
		$groupModel = $this->loadModel('groups');
		$groupModel->insertUserGroup($insertId,$this->stack['static_var']['user_register_group']);
		
		//发送注册邮件
		$this->result['mailer']['subject'] = '"'.$this->stack['static_var']['blog_name'].'"注册提示';
		$this->result['mailer']['body'] = $_POST['user_name'].",您好:\r\n欢迎您成为我们网站的用户.\r\n您注册的用户名是'".$_POST['user_name']."',密码是'".$password."'\r\n\r\n感谢您的支持! \r\n".$this->stack['static_var']['siteurl'];
		$this->result['mailer']['send_to'] = $_POST['user_mail'];
		$this->result['mailer']['send_to_user'] = $_POST['user_name'];
		
		//登录用户
		$_SESSION['user_name'] = $_POST['user_name'];
		$_SESSION['user_id'] = $insertId;
		$_SESSION['user_group'] = array($this->stack['static_var']['user_register_group']);
		$_SESSION['auth_data'] = mgCreateRandomString(128);
		
		setcookie('auth_data',$_SESSION['auth_data'],0,'/');
	}
	
	public function runModule()
	{
		$this->result = array();
		if($this->stack['static_var']['user_allow_register'])
		{
			$this->onPost("do","insertUser","register");
		}
		
		return $this->result;
	}
}
?>
