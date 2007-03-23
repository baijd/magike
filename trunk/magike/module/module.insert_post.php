<?php
/**********************************
 * Created on: 2007-3-21
 * File Name : module.insert_post.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class InsertPost extends MagikeModule
{
	public function insertPost()
	{
		
	}
	
	public function runModule()
	{
		$this->onPost('do','insertPost','insert');
	}
}
?>
