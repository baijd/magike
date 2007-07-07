<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.insert_user.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class InsertUser extends MagikeModule
{
	public function runModule()
	{
		if(isset($_GET['user_id']))
		{
			$userModel = $this->loadModel('users');
			$result = $userModel->fetchOneByKey($_GET['user_id']);
			if(isset($this->stack['admin_menu_list']['children']))
			{
				$this->stack['admin_menu_list']['children'][1]['menu_name'] = '编辑用户 "'.$result['user_name'].'"';
				$this->stack['admin_menu_list']['children'][1]['path_name'] = '/admin/users/user/?user_id='.$result['id'];
				$this->stack['static_var']['admin_title'] = '编辑用户 "'.$result['user_name'].'"';
			}
			
			$groupModel = $this->loadModel('groups');
			$result['user_group'] = array();
			$result['do'] = 'update';
			$groups = $groupModel->getUserGroups($_GET['user_id']);
			foreach($groups as $val)
			{
				$result['user_group'][] = $val['group_id'];
			}
			
			return $result;
		}
		else
		{
			return array('user_group' => array(),'do' => 'insert');
		}
	}
}
?>
