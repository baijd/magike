<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.files_list.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class FilesList extends PageNavigator
{
	public function runModule($args)
	{
		$require = array('limit'  => 20);
		$getArgs = $this->initArgs($args,$require);
		
		$fileModel = $this->loadModel('files');
		$total = $fileModel->countTable();
		
		return $this->makeClassicNavigator($getArgs['limit'],$total,'admin/posts/upload/?page=');
	}
}
?>
