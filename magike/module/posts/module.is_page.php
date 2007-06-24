<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.is_page.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
 class IsPage extends Posts
 {	
	public function runModule()
	{		
		return $this->model->listAllPages();
	}
 }
?>
