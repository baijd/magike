<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.skin_file_input.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class SkinFileInput extends MagikeModule
{
	private $result;

	function __construct()
	{
		parent::__construct();
		$this->result = array();
		$this->result['open'] = false;
	}
	
	public function updateSkinFile()
	{
		$this->requirePost("file",false);
		
		$skin = isset($_POST['skin']) ? $_POST['skin'] : $this->stack['static_var']['template'];
		$path = __TEMPLATE__.'/'.$skin.'/'.$_POST['file'];
		
		$this->result['open'] = true;
		if(is_writeable($path))
		{
			file_put_contents($path,$_POST["file_content"]);
			$this->result['word'] = '您的文件 "'.$_POST['file'].'" 已经被成功更新';
		}
		else
		{
			$this->result['word'] = '文件 "'.$_POST['file'].'" 不可写,请设置文件权限';
		}
	}
	
	public function runModule()
	{
		$this->onPost("do","updateSkinFile","update");
		return $this->result;
	}
}
?>
