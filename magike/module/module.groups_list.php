<?php
/**********************************
 * Created on: 2007-3-4
 * File Name : module.groups_list.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class GroupsList extends MagikeModule
{
	public function runModule()
	{
		$groupModel = $this->loadModel('groups');
		return $groupModel->listGroups();
	}
}
?>
