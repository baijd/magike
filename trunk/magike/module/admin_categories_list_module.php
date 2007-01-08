<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : admin_categories_list_module.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class AdminCategoriesListModule extends MagikeModule
{
	public function runModule()
	{
		$this->template->data['admin_categories_list'] = $this->database->fectch(array('table' => 'table.categories'));
	}
}
?>
