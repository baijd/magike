<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.skin_input.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class SkinInput extends MagikeModule
{
	private $result;

	function __construct()
	{
		parent::__construct();
		$this->result = array();
		$this->result['open'] = false;
	}
	
	public function changeSkin()
	{
		$staticModel = $this->loadModel('statics');
		$staticModel->updateByFiledEqual('static_name','template',array('static_value' => $_GET['tpl']));
		
		$this->stack['static_var']['template'] = $_GET['tpl'];
		$this->deleteCache('static_var');
		$this->result['open'] = true;
		$this->result['word'] = '您的网站风格已经成功被改变为 "'.$_GET['tpl'].'"';
	}
	
	public function runModule()
	{
		$this->onGet("tpl","changeSkin");
		return $this->result;
	}
}
?>
