<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.comment_input.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class CommentInput extends MagikeModule
{
	private $result;
	
	function __construct()
	{
		parent::__construct();
		$this->result = array();
		$this->result['open'] = false;
	}
	
	public function spamComment()
	{
		$select = is_array($_GET['comment_id']) ? $_GET['comment_id'] : array($_GET['comment_id']);
		$postModel = $this->loadModel('posts');
		$commentsModel = $this->loadModel('comments');
		$post = array();

		foreach($select as $id)
		{
			$comment = $commentsModel->fectchByKey($id);
			if($comment['comment_publish'] == 'approved')
			{
				$post[] = $comment['post_id'];
			}
			$commentsModel->updateByKey($id,array('comment_publish' => 'spam'));
		}
		
		$staticModel = $this->loadModel('statics');
		$staticModel->decreaseValueByName('count_comments',count($post));
		$this->deleteCache('static_var');
		
		foreach($post as $id)
		{
			$postModel->decreaseFieldByKey($id,'post_comment_num');
		}
		$this->result['open'] = true;
		$this->result['word'] = '评论已经成功被标记为垃圾';
	}
	
	public function wattingComment()
	{
		$select = is_array($_GET['comment_id']) ? $_GET['comment_id'] : array($_GET['comment_id']);
		$postModel = $this->loadModel('posts');
		$commentsModel = $this->loadModel('comments');
		$post = array();

		foreach($select as $id)
		{
			$comment = $commentsModel->fectchByKey($id);
			if($comment['comment_publish'] == 'approved')
			{
				$post[] = $comment['post_id'];
			}
			$commentsModel->updateByKey($id,array('comment_publish' => 'waitting'));
		}
		
		$staticModel = $this->loadModel('statics');
		$staticModel->decreaseValueByName('count_comments',count($post));
		$this->deleteCache('static_var');
		
		foreach($post as $id)
		{
			$postModel->decreaseFieldByKey($id,'post_comment_num');
		}
		$this->result['open'] = true;
		$this->result['word'] = '评论已经成功被标记为待审核';
	}
	
	public function approvedComment()
	{
		$select = is_array($_GET['comment_id']) ? $_GET['comment_id'] : array($_GET['comment_id']);
		$postModel = $this->loadModel('posts');
		$commentsModel = $this->loadModel('comments');
		$post = array();

		foreach($select as $id)
		{
			$comment = $commentsModel->fectchByKey($id);
			if($comment['comment_publish'] == 'spam' || $comment['comment_publish'] == 'waitting')
			{
				$post[] = $comment['post_id'];
			}
			$commentsModel->updateByKey($id,array('comment_publish' => 'approved'));
		}
		
		$staticModel = $this->loadModel('statics');
		$staticModel->increaseValueByName('count_comments',count($post));
		$this->deleteCache('static_var');
		
		foreach($post as $id)
		{
			$postModel->increaseFieldByKey($id,'post_comment_num');
		}
		$this->result['open'] = true;
		$this->result['word'] = '评论已经成功被标记为展现';
	}
	
	public function deleteComment()
	{
		$select = is_array($_GET['comment_id']) ? $_GET['comment_id'] : array($_GET['comment_id']);
		$postModel = $this->loadModel('posts');
		$commentsModel = $this->loadModel('comments');
		$post = array();

		foreach($select as $id)
		{
			$comment = $commentsModel->fectchByKey($id);
			if($comment['comment_publish'] == 'approved')
			{
				$post[] = $comment['post_id'];
			}
			$commentsModel->deleteByKeys($id);
		}
		
		$staticModel = $this->loadModel('statics');
		$staticModel->decreaseValueByName('count_comments',count($post));
		$this->deleteCache('static_var');
		
		foreach($post as $id)
		{
			$postModel->increaseFieldByKey($id,'post_comment_num');
		}
		$this->result['open'] = true;
		$this->result['word'] = '评论已经被成功删除';
	}
	
	public function runModule()
	{
		$this->onGet("do","spamComment","spam");
		$this->onGet("do","wattingComment","waitting");
		$this->onGet("do","approvedComment","approved");
		$this->onGet("do","deleteComment","del");
		return $this->result;
	}
}
?>
