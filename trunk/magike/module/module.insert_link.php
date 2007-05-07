<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.insert_link.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class InsertLink extends MagikeModule
{
	public function runModule()
	{
		if(isset($_GET['link_id']))
		{
			$linkModel = $this->loadModel('links');
			$result = $linkModel->fectchByKey($_GET['link_id']);
			$result['do'] = 'update';
			$this->stack['admin_menu_list']['children'][1]['menu_name'] = '编辑链接 "'.$result['link_name'].'"';
			$this->stack['admin_menu_list']['children'][1]['path_name'] = '/admin/posts/category/?c='.$result['id'];
			return $result;
		}
		else
		{
			return array('link_category_id' => 0,'do' => 'insert');
		}
	}
}
?>
