<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.user_input.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class UserInput extends MagikeModule
{
	private $result;

	function __construct()
	{
		parent::__construct();
		$this->result = array();
		$this->result['open'] = false;
	}
	
	public function updateUser()
	{
		$this->requirePost(NULL,false);
		$this->requireGet('user_id');
		$userModel = $this->loadModel('users');
		$args = array('user_name' => $_POST['user_name'],
					  'user_firstname'	=> $_POST['user_firstname'],
					  'user_lastname'	=> $_POST['user_lastname'],
					  'user_mail'	=> $_POST['user_mail'],
					  'user_url'	=> $_POST['user_url'],
					  'user_nick' => $_POST['user_nick'],
					  'user_about'	=> $_POST['user_about'],
					  'user_group'	=> $_POST['user_group']);
		if(isset($_POST['user_password']) && $_POST['user_password'])
		{
			$args['user_password'] = md5($_POST['user_password']);
		}
		$userModel->updateByKey($_GET['user_id'],$args);
		
		$this->result['open'] = true;
		$this->result['word'] = '您的用户"'.$_POST['user_name'].'" 已经更新成功';
	}
	
	public function insertUser()
	{
		$this->requirePost(NULL,false);
		$userModel = $this->loadModel('users');
		
		$password = isset($_POST['user_password']) && $_POST['user_password'] ? $_POST['user_password'] : mgCreateRandomString(7);
		$insertId = 
		$userModel->insertTable(array('user_name' => $_POST['user_name'],
									  'user_firstname'	=> $_POST['user_firstname'],
									  'user_lastname'	=> $_POST['user_lastname'],
									  'user_password'	=> md5($password),
									  'user_mail'	=> $_POST['user_mail'],
									  'user_url'	=> $_POST['user_url'],
									  'user_nick' => $_POST['user_nick'],
									  'user_about'	=> $_POST['user_about'],
									  'user_group'	=> $_POST['user_group']));
		
		$this->result['open'] = true;
		$this->result['word'] = '您的用户 "'.$_POST['user_name'].'" 已经提交成功'.(isset($_POST['user_password']) && $_POST['user_password'] ? '' : ',密码为<strong>'.$password.'</strong>');
	}
	
	public function deleteUser()
	{
		$this->requireGet('user_id');
		$select = is_array($_GET['user_id']) ? $_GET['user_id'] : array($_GET['user_id']);
		$userModel = $this->loadModel('users');
		$userModel->deleteByKeys($select,array(1));
		
		$this->result['open'] = true;
		$this->result['word'] = '您删除的用户已经生效';
	}
	
	public function runModule()
	{
		$this->onGet("do","updateUser","update");
		$this->onGet("do","insertUser","insert");
		$this->onGet("do","deleteUser","del");
		return $this->result;
	}
}
?>
