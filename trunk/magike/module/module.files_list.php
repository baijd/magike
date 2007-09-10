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
		$val['file_id'] = $val['id'];
		$val["permalink"] = $this->stack['static_var']['index'].vsprintf($this->stack['permalink']['file_output']['path'],mgArrayIntersectKey($val,$this->stack['permalink']['file_output']['value']));
		$val["thumbnail_permalink"] = $this->stack['static_var']['index'].vsprintf($this->stack['permalink']['thumbnail']['path'],mgArrayIntersectKey($val,$this->stack['permalink']['thumbnail']['value']));
		$val["view_thumbnail_permalink"] = $this->stack['static_var']['index'].vsprintf($this->stack['permalink']['view_thumbnail']['path'],mgArrayIntersectKey($val,$this->stack['permalink']['view_thumbnail']['value']));
		return $val;
	}
	
	public function runModule($args)
	{
		$require = array('limit' => 20);
		$getArgs = $this->initArgs($args,$require);
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$fileModel = $this->loadModel('files');
		return $fileModel->listFiles($getArgs['limit'],($page-1)*$getArgs['limit'],array('function' => array($this,'praseFile')));
	}
}
?>
