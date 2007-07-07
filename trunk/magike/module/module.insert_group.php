<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.insert_group.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class InsertGroup extends MagikeModule
{
	public function runModule()
	{
		if(isset($_GET['group_id']))
		{
			$groupModel = $this->loadModel('groups');
			$result = $groupModel->fetchOneByKey($_GET['group_id']);
			$result['do'] = 'update';
			$this->stack['admin_menu_list']['children'][3]['menu_name'] = '编辑用户组 "'.$result['group_name'].'"';
			$this->stack['admin_menu_list']['children'][3]['path_name'] = '/admin/users/group/?group_id='.$result['id'];
			$this->stack['static_var']['admin_title'] = '编辑用户组 "'.$result['group_name'].'"';
			
			$groupPath = array();
			$path = $groupModel->getGroupPaths($_GET['group_id']);
			foreach($path as $val)
			{
				$groupPath[] = $val['path_id'];
			}
			$result['group_path'] = $groupPath;
			return $result;
		}
		else
		{
			return array('group_path' => array(),'do' => 'insert');
		}
	}
}
?>
