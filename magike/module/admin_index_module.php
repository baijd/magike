<?php
/**********************************
 * Created on: 2006-12-17
 * File Name : admin_index.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class AdminIndexModule extends MagikeModule
{
	public function runModule()
	{
		$this->template->data['admin_index']['server_version'] = PHP_OS." PHP ".PHP_VERSION;
		$this->template->data['admin_index']['magike_version'] = $this->stack->data['static']['version'];
		$this->template->data['admin_index']['posts_num'] 	   = $this->database->count(array('table' => 'table.posts','key' => 'id'));
	}
}
?>
