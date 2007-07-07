<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.link_category_input.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class LinkCategoryInput extends MagikeModule
{
	private $result;
	private $category;

	function __construct()
	{
		parent::__construct();
		$this->result = array();
		$this->result['open'] = false;
		$this->model = $this->loadModel('link_categories');
	}

	public function moveUpLinkCategory()
	{
		$this->requireGet('lc_id');
		$title = $this->model->moveUpCategory($_GET['lc_id']);
		
		$this->result['open'] = true;
		$this->result['word'] = '您的链接分类 "'.$title.'" 已经移动成功';
	}
	
	public function moveDownLinkCategory()
	{
		$this->requireGet('lc_id');
		$title = $this->model->moveDownCategory($_GET['lc_id']);
		
		$this->result['open'] = true;
		$this->result['word'] = '您的链接分类 "'.$title.'"已经移动成功';
	}
	
	public function updateLinkCategory()
	{
		$this->requirePost();
		$this->requireGet('lc_id');
		$this->model->updateByKey($_GET['lc_id'], array('link_category_name' 		=> $_POST['link_category_name'],
													   'link_category_hide'		=> $_POST['link_category_name'],
													   'link_category_describe'	=> $_POST['link_category_describe'],
													   'link_category_linksort'	=> $_POST['link_category_linksort'])
									  );
		
		$this->result['open'] = true;
		$this->result['word'] = '您的链接分类 "'.$_POST['link_category_name'].'" 已经更新成功';
	}
	
	public function insertLinkCategory()
	{
		$this->requirePost();
		$item = $this->model->fetchOne(array('fields'=> 'MAX(link_category_sort) AS max_sort',
											  'table' => 'table.link_categories'));
		$this->model->insertTable(array('link_category_name' 	=> $_POST['link_category_name'],
										   'link_category_hide'		=> $_POST['link_category_hide'],
										   'link_category_describe'	=> $_POST['link_category_describe'],
										   'link_category_linksort'	=> $_POST['link_category_linksort'],
										   'link_category_sort'		=> $item['max_sort']+1));
		
		$this->result['open'] = true;
		$this->result['word'] = '您的链接分类 "'.$_POST['link_category_name'].'" 已经提交成功';
	}
	
	public function deleteLinkCategory()
	{
		$this->requireGet('lc_id');
		$select = is_array($_GET['lc_id']) ? $_GET['lc_id'] : array($_GET['lc_id']);
		$linkModel = $this->loadModel('links');
		
		foreach($select as $id)
		{
			$this->model->deleteByKeys($id);
			$linkModel->deleteByFieldEqual('link_category_id',$id);
		}
		
		$this->result['open'] = true;
		$this->result['word'] = '您删除的链接分类已经生效';
	}
	
	public function runModule()
	{
		$this->onGet("do","moveUpLinkCategory","up");
		$this->onGet("do","moveDownLinkCategory","down");
		$this->onGet("do","updateLinkCategory","update");
		$this->onGet("do","insertLinkCategory","insert");
		$this->onGet("do","deleteLinkCategory","del");
		return $this->result;
	}
}
?>
