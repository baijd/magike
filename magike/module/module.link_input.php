<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.link_input.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class LinkInput extends MagikeModule
{
	private $result;

	function __construct()
	{
		parent::__construct();
		$this->result = array();
		$this->result['open'] = false;
	}
	
	public function updateLink()
	{
		$this->requirePost();
		$this->requireGet('link_id');
		$linkModel = $this->loadModel('links');
		$linkModel->updateByKey($_GET['link_id'], array('link_name' 		=> $_POST['link_name'],
													   	  'link_describe'	=> $_POST['link_describe'],
													   	  'link_url'	=> $_POST['link_url'],
														  'link_image'	=> $_POST['link_image'],
														  'link_category_id'	=> $_POST['link_category_id'])
									  );
		
		$this->result['open'] = true;
		$this->result['word'] = '您的链接 "'.$_POST['link_name'].'" 已经更新成功';
	}
	
	public function insertLink()
	{
		$this->requirePost();
		$linkModel = $this->loadModel('links');
		$linkModel->insertTable(array('link_name' => $_POST['link_name'],
										'link_describe'	=> $_POST['link_describe'],
										'link_url'	=> $_POST['link_url'],
										'link_image'	=> $_POST['link_image'],
										'link_category_id'	=> $_POST['link_category_id']));
		
		$this->result['open'] = true;
		$this->result['word'] = '您的链接 "'.$_POST['link_name'].'" 已经提交成功';
	}
	
	public function deleteLink()
	{
		$this->requireGet('link_id');
		$select = is_array($_GET['link_id']) ? $_GET['link_id'] : array($_GET['link_id']);
		$linkModel = $this->loadModel('links');
		$linkModel->deleteByKeys($_GET['link_id']);
		
		$this->result['open'] = true;
		$this->result['word'] = '您删除的链接已经生效';
	}
	
	public function runModule()
	{
		$this->onGet("do","updateLink","update");
		$this->onGet("do","insertLink","insert");
		$this->onGet("do","deleteLink","del");
		return $this->result;
	}
}
?>
