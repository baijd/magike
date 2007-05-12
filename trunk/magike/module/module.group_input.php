<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.group_input.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class GroupInput extends MagikeModule
{
	private $result;

	function __construct()
	{
		parent::__construct();
		$this->result = array();
		$this->result['open'] = false;
	}
	
	public function updateGroup()
	{
		$this->requirePost();
		$this->requireGet('group_id');
		$groupModel = $this->loadModel('groups');
		$args = array('group_name' => $_POST['group_name'],
					  'group_describe'	=> $_POST['group_describe']);
		$groupModel->updateByKey($_GET['group_id'],$args);
		
		$groupModel->deletePathGroup($_GET['group_id']);
		if(isset($_POST['group_path']) && $_POST['group_path'])
		{
			foreach($_POST['group_path'] as $id)
			{
				$groupModel->insertPathGroup($id,$_GET['group_id']);
			}
		}
		
		$this->deleteCache('access');
		$this->result['open'] = true;
		$this->result['word'] = '您的用户组 "'.$_POST['group_name'].'" 已经更新成功';
	}
	
	public function insertGroup()
	{
		$this->requirePost();
		$groupModel = $this->loadModel('groups');
		
		$insertId = 
		$groupModel->insertTable(array('group_name' => $_POST['group_name'],
					  			'group_describe'	=> $_POST['group_describe']));
		
		if(isset($_POST['group_path']) && $_POST['group_path'])
		{
			foreach($_POST['group_path'] as $id)
			{
				$groupModel->insertPathGroup($id,$insertId);
			}
		}
		
		$this->deleteCache('access');
		$this->result['open'] = true;
		$this->result['word'] = '您的用户组 "'.$_POST['group_name'].'" 已经提交成功';
	}
	
	public function deleteGroup()
	{
		$this->requireGet('group_id');
		$select = is_array($_GET['group_id']) ? $_GET['group_id'] : array($_GET['group_id']);
		$groupModel = $this->loadModel('users');
		$groupModel = $this->loadModel('groups');
		$groupModel->deleteByKeys($_GET['group_id']);
		foreach($select as $id)
		{
			$groupModel->deletePathGroup($id);
			$groupModel->deleteGroupUser($id);
		}
		
		$this->deleteCache('access');
		$this->result['open'] = true;
		$this->result['word'] = '您删除的用户组已经生效';
	}
	
	public function runModule()
	{
		$this->onGet("do","updateGroup","update");
		$this->onGet("do","insertGroup","insert");
		$this->onGet("do","deleteGroup","del");
		return $this->result;
	}
}
?>
