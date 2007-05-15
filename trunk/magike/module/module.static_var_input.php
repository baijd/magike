<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.static_var_input.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class StaticVarInput extends MagikeModule
{
	private $result;

	function __construct()
	{
		parent::__construct();
		$this->result = array();
		$this->result['open'] = false;
	}
	
	public function updateStaticVar()
	{
		$this->requirePost();
		$staticModel = $this->loadModel('statics');
		$input = $_POST;
		unset($input['do']);
		foreach($input as $key => $val)
		{
			$this->stack['static_var'][$key] = $val;
			$staticModel->updateByFiledEqual('static_name',$key,array('static_value' => $val));
		}
		
		$this->deleteCache('static_var');
		$this->result['open'] = true;
		$this->result['word'] = '您的配置已经更新成功';
	}
	
	public function runModule()
	{
		$this->onPost("do","updateStaticVar","update");
		return $this->result;
	}
}
?>
