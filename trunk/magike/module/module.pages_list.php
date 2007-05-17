<?php
/**********************************
 * Created on: 2007-3-4
 * File Name : module.pages_list.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class PagesList extends MagikeModule
{
	public function runModule()
	{
		$postModel = $this->loadModel('posts');
		return $postModel->listAllPages();
	}
}
?>
