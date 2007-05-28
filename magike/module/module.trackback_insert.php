<?php
/**********************************
 * Created on: 2007-3-4
 * File Name : module.trackback_insert.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class TrackbackInsert extends MagikeModule
{
	private $result;
	
	public function insertTrackback()
	{
		$postModel = $this->loadModel('posts');
		$commentModel = $this->loadModel('comments');
		$post = array();
		$input = array();
		
		if(isset($_GET['post_id']))
		{
			$post = $postModel->fectchPostById($_GET['post_id'],NULL,false);
		}
		else if(isset($_GET['post_name']))
		{
			$post = $postModel->fectchPostByName($_GET['post_name'],NULL,false);
		}
		
		if($post && $post['post_allow_ping'])
		{
			$this->result['success'] = '0';
			$this->result['word'] = 'success!';
			
			$input['comment_publish'] = 'approved';
			$input['comment_date'] = time();
			$input['comment_gmt'] = $this->stack['static_var']['server_timezone'];
			$input['post_id'] = $post['post_id'];
			$input['comment_type'] = 'ping';
			$input['comment_agent'] = $_SERVER["HTTP_USER_AGENT"];
			$input['comment_ip'] = $_SERVER["REMOTE_ADDR"];
			$input['comment_user'] = isset($_POST['blog_name']) ? $_POST['blog_name'] : NULL;
			$input['comment_title'] = isset($_POST['title']) ? $_POST['title'] : NULL;
			$input['comment_homepage'] = isset($_POST['url']) ? $_POST['url'] : NULL;
			$input['comment_text'] = isset($_POST['excerpt']) ? $_POST['excerpt'] : NULL;
			
			if(NULL == $input['comment_homepage'])
			{
				$this->result['success'] = '1';
				$this->result['word'] = 'We require all Trackbacks to provide an url.';
				return;
			}
			if(NULL == $input['comment_text'])
			{
				$this->result['success'] = '1';
				$this->result['word'] = 'We require all Trackbacks to provide an excerption.';
				return;
			}
			if($this->stack['static_var']['comment_check'])
			{
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
			$this->result['success'] = '1';
			$this->result['word'] = 'Invalid ID or the ID refers to a locked entry.';
		}
	}
	
	public function runModule()
	{
		$this->insertTrackback();
		return $this->result;
	}
}
?>
