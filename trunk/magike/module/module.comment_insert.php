<?php
/**********************************
 * Created on: 2007-3-4
 * File Name : module.comment_insert.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class CommentInsert extends MagikeModule
{
	public function insertComment()
	{
		$postModel = $this->loadModel('posts');
		$commentModel = $this->loadModel('comments');
		$post = array();
		$input = array();
		
		if(isset($_GET['post_id']))
		{
			$post = $postModel->fectchPostById($_GET['post_id']);
		}
		else if(isset($_GET['post_name']))
		{
			$post = $postModel->fectchPostByName($_GET['post_name']);
		}
		
		if($post && $post['post_allow_comment'])
		{
			$result['open'] = true;
			$result['word'] = '感谢您的参与,您的评论已经提交';
			
			$input['comment_publish'] = 'approved';
			$input['comment_date'] = time();
			$input['comment_gmt'] = $this->stack['static']['server_timezone'];
			$input['post_id'] = $post['post_id'];
			$input['comment_type'] = 'comment';
			$input['comment_agent'] = $_SERVER["HTTP_USER_AGENT"];
			$input['comment_ip'] = $_SERVER["REMOTE_ADDR"];
			$input['comment_user'] = isset($_POST['comment_user']) ? $_POST['comment_user'] : NULL;
			$input['comment_email'] = isset($_POST['comment_email']) ? $_POST['comment_email'] : NULL;
			$input['comment_homepage'] = isset($_POST['comment_homepage']) ? $_POST['comment_homepage'] : NULL;
			
			if($this->stack['static_var']['comment_check'])
			{
				$result['word'] = '您的评论正在等待审核';
				$input['comment_publish'] = 'waitting';
			}
			if(isset($this->stack['comment_filter']) && $this->stack['comment_filter'])
			{
				$result['word'] = $this->stack['comment_filter']['word'];
				$input['comment_publish'] = $this->stack['comment_filter']['publish'];
			}
			
			$commentModel->insertTable($input);
			$this->result = $result;
		}
	}
	
	public function runModule($args)
	{
		$this->result = array();
		$require = array('key' => 'do','val' => 'insert');
		$getArgs = $this->initArgs($args,$require);

		$this->onPost($getArgs['key'],'insertComment',$getArgs['val']);
		return $this->result;
	}
}
?>
