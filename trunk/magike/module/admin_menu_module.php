<?php
/**********************************
 * Created on: 2006-12-19
 * File Name : admin_menu_module.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class ArchivesModule extends MagikeModule
{
	public function runModule()
	{
		$this->template->data['admin_menu']	= $this->database->fectch(array('table' => 'table.menu',
																		  	'where' => array('template' => 'mn_parent = 0'
																		  	)));
	}
}
?>
