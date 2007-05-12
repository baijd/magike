<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.path_input.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class PathInput extends MagikeModule
{
	private $result;

	function __construct()
	{
		parent::__construct();
		$this->result = array();
		$this->result['open'] = false;
	}
	
	private function updateCache()
	{
		$this->deleteCache('access');
		$this->deleteCache('action');
	}
	
	public function updatePath()
	{
		$this->requirePost();
		$this->requireGet('path_id');
		$groupModel = $this->loadModel('groups');
		$pathModel = $this->loadModel('paths');
		$args = array('path_name' => $_POST['path_name'],
					  'path_action' => $_POST['path_action'],
					  'path_file' => $_POST['path_file'],
					  'path_describe'	=> $_POST['path_describe']);
		$pathModel->updateByKey($_GET['path_id'],$args);
		
		$groupModel->deleteGroupPath($_GET['path_id']);
		if(isset($_POST['path_group']) && $_POST['path_group'])
		{
			foreach($_POST['path_group'] as $id)
			{
				$groupModel->insertPathGroup($_GET['path_id'],$id);
			}
		}
		
		$this->updateCache();
		$this->result['open'] = true;
		$this->result['word'] = '您的路径 "'.$_POST['path_describe'].'" 已经更新成功';
	}
	
	public function insertPath()
	{
		$this->requirePost();
		$groupModel = $this->loadModel('groups');
		$pathModel = $this->loadModel('paths');
		
		$insertId = 
		$pathModel->insertTable(array('path_name' => $_POST['path_name'],
					  'path_action' => $_POST['path_action'],
					  'path_file' => $_POST['path_file'],
					  'path_describe'	=> $_POST['path_describe']));
		
		if(isset($_POST['path_group']) && $_POST['path_group'])
		{
			foreach($_POST['path_group'] as $id)
			{
				$groupModel->insertPathGroup($insertId,$id);
			}
		}
		
		$this->updateCache();
		$this->result['open'] = true;
		$this->result['word'] = '您的路径 "'.$_POST['path_describe'].'" 已经提交成功';
	}
	
	public function deletePath()
	{
		$this->requireGet('path_id');
		$select = is_array($_GET['path_id']) ? $_GET['path_id'] : array($_GET['path_id']);
		$pathModel = $this->loadModel('paths');
		$groupModel = $this->loadModel('groups');
		$pathModel->deleteByKeys($_GET['path_id']);
		foreach($select as $id)
		{
			$groupModel->deleteGroupPath($id);
		}
		
		$this->updateCache();
		$this->result['open'] = true;
		$this->result['word'] = '您删除的路径已经生效';
	}
	
	public function runModule()
	{
		$this->onGet("do","updatePath","update");
		$this->onGet("do","insertPath","insert");
		$this->onGet("do","deletePath","del");
		return $this->result;
	}
}
?>
