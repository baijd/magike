<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.insert_link_category.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class InsertLinkCategory extends MagikeModule
{
	public function runModule()
	{
		if(isset($_GET['lc_id']))
		{
			$linkCategoryModel = $this->loadModel('link_categories');
			$result = $linkCategoryModel->fectchByKey($_GET['lc_id']);
			if(isset($this->stack['admin_menu_list']['children']))
			{
				$this->stack['admin_menu_list']['children'][3]['menu_name'] = '编辑链接分类 "'.$result['link_category_name'].'"';
				$this->stack['admin_menu_list']['children'][3]['path_name'] = '/admin/links/link_category/?lc_id='.$result['id'];
				$this->stack['static_var']['admin_title'] = '编辑链接分类 "'.$result['link_category_name'].'"';
			}
			$result['do'] = 'update';
			return $result;
		}
		else
		{
			return array('do' => 'insert','link_category_hide' => 0,'link_category_linksort' => 'asc');
		}
	}
}
?>
