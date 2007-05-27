<?php
/**********************************
 * Created on: 2007-3-4
 * File Name : module.comment_insert.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class CommentInsert extends MagikeModule
{
	private $showWord;
	private $result;
	
	public function insertComment()
	{
		$this->showWord = false;
		if($this->stack['static_var']['comment_ajax_validator'])
		{
			$this->requirePost();
		}
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
			$this->result['open'] = true;
			$this->result['word'] = '感谢您的参与,您的评论已经提交';
			
			$input['comment_publish'] = 'approved';
			$input['comment_date'] = time();
			$input['comment_gmt'] = $this->stack['static_var']['server_timezone'];
			$input['post_id'] = $post['post_id'];
			$input['comment_type'] = 'comment';
			$input['comment_agent'] = $_SERVER["HTTP_USER_AGENT"];
			$input['comment_ip'] = $_SERVER["REMOTE_ADDR"];
			$input['comment_user'] = isset($_POST['comment_user']) ? $_POST['comment_user'] : NULL;
			$input['comment_email'] = isset($_POST['comment_email']) ? $_POST['comment_email'] : NULL;
			$input['comment_homepage'] = isset($_POST['comment_homepage']) ? $_POST['comment_homepage'] : NULL;
			$input['comment_text'] = isset($_POST['comment_text']) ? $_POST['comment_text'] : NULL;
			
			if(NULL == $input['comment_user'])
			{
				$this->result['word'] = '对不起,您必须填写用户名';
				$this->showWord = true;
				return;
			}
			if(NULL == $input['comment_text'])
			{
				$this->result['word'] = '对不起,评论内容不能为空';
				$this->showWord = true;
				return;
			}
			if($this->stack['static_var']['comment_email_notnull'] && NULL == $input['comment_email'])
			{
				$this->result['word'] = '对不起,您必须填写电子邮件';
				$this->showWord = true;
				return;
			}
			if($this->stack['static_var']['comment_homepage_notnull'] && NULL == $input['comment_homepage'])
			{
				$this->result['word'] = '对不起,您必须填写个人主页';
				$this->showWord = true;
				return;
			}
			if($this->stack['static_var']['comment_check'])
			{
				$this->result['word'] = '您的评论正在等待审核';
				$input['comment_publish'] = 'waitting';
			}
			if(isset($this->stack['comment_filter']) && $this->stack['comment_filter'])
			{
				$this->result['word'] = $this->stack['comment_filter']['word'];
				$input['comment_publish'] = $this->stack['comment_filter']['publish'];
			}
			
			$commentModel->insertTable($input);
			if($input['comment_publish'] == 'approved')
			{
				$postModel->increaseFieldByKey($post['post_id'],'post_comment_num');
				$staticModel = $this->loadModel('statics');
				$staticModel->increaseValueByName('count_comments');
				$this->deleteCache('static_var');
			}
		}
		else
		{
			$this->result['open'] = true;
			$this->result['word'] = '对不起,该文章禁止评论';
		}
	}
	
	public function runModule($args)
	{
		$this->result = array();
		$require = array('key' => 'do','val' => 'insert');
		$getArgs = $this->initArgs($args,$require);

		$this->onPost($getArgs['key'],'insertComment',$getArgs['val']);
		if(isset($_GET['referer']))
		{
			if($this->showWord)
			{
				return $this->result;
			}
			else
			{
				header('location: '.$_GET['referer']);
			}
		}
		else
		{
			header('location: '.$this->stack['static_var']['siteurl']);
		}
		
		return $this->result;
	}
}
?>
