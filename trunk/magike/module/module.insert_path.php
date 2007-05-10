<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.insert_path.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class InsertPath extends MagikeModule
{
	public function runModule()
	{
		if(isset($_GET['path_id']))
		{
			$pathModel = $this->loadModel('paths');
			$result = $pathModel->fectchByKey($_GET['path_id']);
			if(isset($this->stack['admin_menu_list']['children']))
			{
				$this->stack['admin_menu_list']['children'][1]['menu_name'] = '编辑路径 "'.$result['path_describe'].'"';
				$this->stack['admin_menu_list']['children'][1]['path_name'] = '/admin/paths/path/?path_id='.$result['id'];
				$this->stack['static_var']['admin_title'] = '编辑路径 "'.$result['path_describe'].'"';
			}
			
			$groupModel = $this->loadModel('groups');
			$result['path_group'] = array();
			$result['do'] = 'update';
			$groups = $groupModel->getPathGroups($_GET['path_id']);
			foreach($groups as $val)
			{
				$result['path_group'][] = $val['group_id'];
			}
			
			return $result;
		}
		else
		{
			return array('path_group' => array(),'do' => 'insert');
		}
	}
}
?>
