<?php
/**********************************
 * Created on: 2007-3-6
 * File Name : module.register.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class Register extends MagikeModule
{
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
									  'user_about'	=> isset($_POST['user_about']) ? $_POST['user_url'] : NULL));
		
		if(isset($_POST['user_group']) && $_POST['user_group'])
		{
			$groupModel = $this->loadModel('groups');
			foreach($_POST['user_group'] as $id)
			{
				$groupModel->insertUserGroup($insertId,$id);
			}
		}
		
		$this->result['open'] = true;
		$this->result['word'] = '您的用户 "'.$_POST['user_name'].'" 已经提交成功'.(isset($_POST['user_password']) && $_POST['user_password'] ? '' : ',密码为<strong>'.$password.'</strong>');
	}
	
	public function runModule()
	{
		$this->onGet("do","insertUser","register");
	}
}
?>
