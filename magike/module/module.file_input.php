<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.file_input.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class FileInput extends MagikeModule
{
	private $result;
	
	public function insertFile()
	{
		if(isset($_FILES['file']['name']))
		{
			$guid = mgGetGuid();
			$path = __UPLOAD__.mgGetGuidPath($guid);
			if(!is_dir($path))
			{
				mgMkdir($path);
			}
			
			move_uploaded_file($_FILES['file']['tmp_name'], $path.'/'.$guid);
			$fileModel = $this->loadModel('files');
			$fileModel->insertTable(array('file_name' => $_FILES['file']['name'],
										  'file_type'	=> $_FILES['file']['type'],
										  'file_guid'	=> $guid,
										  'file_size'	=> $_FILES['file']['size'],
										  'file_time'	=> time() - $this->stack['static_var']['server_timezone'],
										  'file_describe' => $_POST['file_describe']));
		}
		
		$this->result['open'] = true;
		$this->result['word'] = '您的文件 "'.$_FILES['file']['name'].'" 已经提交成功';
	}
	
	public function deleteFile()
	{
		$this->requireGet('file_id');
		$select = is_array($_GET['file_id']) ? $_GET['file_id'] : array($_GET['file_id']);
		$fileModel = $this->loadModel('files');
		
		foreach($select as $val)
		{
			$file = $fileModel->fetchOneByKey($val);
			if($file)
			{
				$fileModel->deleteByKeys($val);
				$path = __UPLOAD__.mgGetGuidPath($file['file_guid']).'/'.$file['file_guid'];
				if(file_exists($path))
				{
					unlink($path);
				}
				
				$this->result['open'] = true;
				$this->result['word'] = '您选中的文件已经被删除';
			}
			else
			{
				$this->result['open'] = true;
				$this->result['word'] = '您选中的文件不存在';
			}
		}
	}
	
	public function runModule()
	{
		$this->result = array();
		$this->result['open'] = false;
		
		$this->onPost("do","insertFile","insert");
		$this->onGet("do","deleteFile","del");
		return $this->result;
	}
}
?>
