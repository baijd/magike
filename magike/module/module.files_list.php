<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.files_list.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class FilesList extends MagikeModule
{
	public function praseFile($val)
	{
		$trim = array('png','jpg','jpeg','gif','bmp','tiff');
		$match = eregi("^([_@0-9a-zA-Z\x80-\xff\^\.\%-]{0,})[\.]([0-9a-zA-Z]{1,})$",$val['file_name'],$file_name);
		$val['is_image'] = in_array(strtolower($file_name[2]),$trim);
		return $val;
	}
	
	public function runModule()
	{
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$fileModel = $this->loadModel('files');
		return $fileModel->listFiles(5,$page,array('function' => array($this,'praseFile')));
	}
}
?>
