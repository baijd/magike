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
		if($this->stack['static_var']['comment_ajax_validator'] && !$this->stack['access']['login'])
		{
			$this->requirePost();
		}
		$postModel = $this->loadModel('posts');
		$commentModel = $this->loadModel('comments');
		$post = array();
		$input = array();
		
		if(isset($_GET['post_id']))
		{
			$post = $postModel->fetchPostById($_GET['post_id'],NULL,false);
		}
		else if(isset($_GET['post_name']))
		{
			$post = $postModel->fetchPostByName($_GET['post_name'],NULL,false);
		}
		
		if($post && $post['post_allow_comment'])
		{
			$this->result['open'] = true;
			$this->result['word'] = '感谢您的参与,您的评论已经提交';
			
			$input['comment_publish'] = 'approved';
			$input['comment_date'] = time() - $this->stack['static_var']['server_timezone'];
			$input['post_id'] = $post['post_id'];
			$input['comment_type'] = 'comment';
			$input['comment_agent'] = $_SERVER["HTTP_USER_AGENT"];
			$input['comment_ip'] = $_SERVER["REMOTE_ADDR"];
			$input['comment_user'] = isset($_POST['comment_user']) ? $_POST['comment_user'] : NULL;
			$input['comment_email'] = isset($_POST['comment_email']) ? $_POST['comment_email'] : NULL;
			$input['comment_homepage'] = isset($_POST['comment_homepage']) ? $_POST['comment_homepage'] : NULL;
			$input['comment_text'] = isset($_POST['comment_text']) ? $_POST['comment_text'] : NULL;
			
			$userModel = $this->loadModel('users');
			if($this->stack['access']['login'])
			{
				$user = $userModel->fetchOneByKey($this->stack['access']['user_id']);
				$input['comment_user'] = $user["user_name"];
				$input['comment_email'] = $user["user_mail"];
				$input['comment_homepage'] = $user["user_url"];
			}
			
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
				$this->showWord = true;
			}
			if(isset($this->stack['comment_filter']) && $this->stack['comment_filter'])
			{
				$this->result['word'] = $this->stack['comment_filter']['word'];
				$input['comment_publish'] = $this->stack['comment_filter']['publish'];
				$this->showWord = true;
			}
			
			$commentModel->insertTable($input);
			if($input['comment_publish'] == 'approved')
			{
				$postModel->increaseFieldByKey($post['post_id'],'post_comment_num');
				$staticModel = $this->loadModel('statics');
				$staticModel->increaseValueByName('count_comments');
				$this->deleteCache('static_var');
			}
			
			//发送邮件提示
			if($this->stack['static_var']['comment_email'] && $input['comment_publish'] != 'spam')
			{
				$author = $userModel->fetchOneByKey($post['user_id']);
				if($author['user_mail'])
				{
					$this->result['mailer']['subject'] = '"'.$this->stack['static_var']['blog_name'].'"回响提示';
					$this->result['mailer']['body'] = $input['comment_user'].'在['.
					date('Y-m-d H:i:s',$this->stack['static_var']['time_zone'] + $input['comment_date'])."]说:\r\n".
					mgSubStr(mgStripTags($input['comment_text']),0,100)."\r\n".
					($input['comment_email'] ? "\r\n电子邮箱:".$input['comment_email'] : '').
					($input['comment_homepage'] ? "\r\n网址:".$input['comment_homepage'] : '');
					$this->result['mailer']['send_to'] = $author['user_mail'];
					$this->result['mailer']['send_to_user'] = $author['user_name'];
					
					if($input['comment_publish'] == 'waitting')
					{
						$this->result['mailer']['body'] .= "\r\n[这篇评论等待审核]";
					}
					
					if($input['comment_email'])
					{
						$this->result['mailer']['reply'] = array($input['comment_user'],$input['comment_email']);
					}
				}
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

		if($this->onPost($getArgs['key'],'insertComment',$getArgs['val']))
		{
			if($this->showWord)
			{
				unset($_POST['referer']);
				reset($_POST);
			}
			
			return $this->result;
		}
		else
		{
			header('location: '.$this->stack['static_var']['siteurl']);
		}
	}
}
?>
