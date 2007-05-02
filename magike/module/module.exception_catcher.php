<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.exception_catcher.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class ExceptionCatcher extends MagikeModule
{
	public function runModule()
	{
		print_r($this->stack['action']['data']);
	}
}
?>
