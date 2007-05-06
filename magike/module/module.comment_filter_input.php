<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.comment_filter_input.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class CommentFilterInput extends MagikeModule
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
		if(file_exists(__CACHE__.'/comment_filter/comment_filter.php'))
		{
			unlink(__CACHE__.'/comment_filter/comment_filter.php');
		}
	}
	
	public function updateCommentFilter()
	{
		$this->requirePost();
		$this->requireGet('cf_id');
		$filterModel = $this->loadModel('comment_filters');
		$filterModel->updateByKey($_GET['cf_id'], array('comment_filter_name' 		=> $_POST['comment_filter_name'],
													   'comment_filter_value'	=> $_POST['comment_filter_value'],
													   'comment_filter_type'	=> $_POST['comment_filter_type'])
									  );
		$this->updateCache();

		
		$this->result['open'] = true;
		$this->result['word'] = '您的过滤器 "'.$this->getLanguage($_POST['comment_filter_name'],'comment_filter').'" 已经更新成功';
	}
	
	public function insertCommentFilter()
	{
		$this->requirePost();
		$filterModel = $this->loadModel('comment_filters');
		$filterModel->insertTable(array('comment_filter_name' 	=> $_POST['comment_filter_name'],
										'comment_filter_value'	=> $_POST['comment_filter_value'],
										'comment_filter_type'	=> $_POST['comment_filter_type']));
		$this->updateCache();
		
		$this->result['open'] = true;
		$this->result['word'] = '您的过滤器 "'.$this->getLanguage($_POST['comment_filter_name'],'comment_filter').'" 已经提交成功';
	}
	
	public function deleteCommentFilter()
	{
		$this->requireGet('cf_id');
		$select = is_array($_GET['cf_id']) ? $_GET['cf_id'] : array($_GET['cf_id']);
		$filterModel = $this->loadModel('comment_filters');
		$filterModel->deleteByKeys($_GET['cf_id']);
		$this->updateCache();
		
		$this->result['open'] = true;
		$this->result['word'] = '您删除的过滤器已经生效';
	}
	
	public function runModule()
	{
		$this->onGet("do","updateCommentFilter","update");
		$this->onGet("do","insertCommentFilter","insert");
		$this->onGet("do","deleteCommentFilter","del");
		return $this->result;
	}
}
?>
