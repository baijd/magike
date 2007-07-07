<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.file_output.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class FileOutput extends MagikeModule
{
	public function runModule()
	{
		if($this->stack['static_var']['referer_denny'] && (!isset($_SERVER['HTTP_REFERER']) || 0 !== strpos($_SERVER['HTTP_REFERER'],$this->stack['static_var']['siteurl'])))
		{
			return '对不起,您要访问的资源不存在';
		}
		
		$fileModel = $this->loadModel('files');
		$file = $fileModel->fetchOneByKey($_GET['file_id']);
		if($file && $file['file_name'] == $_GET['file_name'])
		{
			$path = __UPLOAD__.mgGetGuidPath($file['file_guid']).'/'.$file['file_guid'];
			if(file_exists($path))
			{
				$this->stack['static_var']['content_type'] = $file['file_type'];
				return file_get_contents($path);
			}
			else
			{
				return '对不起,您要访问的资源不存在';
			}
		}
		else
		{
			return '对不起,您要访问的资源不存在';
		}
	}
}
?>
