<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.post_input.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class PostInput extends MagikeModule
{
	private $result;
	
	function __construct()
	{
		parent::__construct();
		$this->result = array();
		$this->result['open'] = false;
	}
	
	public function insertPost()
	{
		$input = $_POST;
		$input['post_is_draft'] = isset($_POST['post_is_draft']) ? $_POST['post_is_draft'] : 0;
		$this->requirePost(NULL,!$input['post_is_draft']);
		
		$url = isset($_POST['post_trackback']) ? $_POST['post_trackback'] : NULL;
		unset($input["post_trackback"]);
		$input['post_title'] = isset($_POST['post_title']) && $_POST['post_title'] ? $_POST['post_title'] : ($input['post_is_draft'] ? '无标题文档' : NULL);
		$input['post_content'] = isset($_POST['post_content'])  && $_POST['post_content'] ? $_POST['post_content'] : NULL;
		$input['post_allow_ping'] = isset($_POST['post_allow_ping']) ? $_POST['post_allow_ping'] : 0;
		$input['post_allow_comment'] = isset($_POST['post_allow_comment']) ? $_POST['post_allow_comment'] : 0;
		$input['post_allow_feed'] = isset($_POST['post_allow_feed']) ? $_POST['post_allow_feed'] : 0;
		$input['post_is_hidden'] = isset($_POST['post_is_hidden']) ? $_POST['post_is_hidden'] : 0;
		$input['post_is_page'] = isset($_POST['post_is_page']) ? $_POST['post_is_page'] : 0;
		$input['post_edit_time'] = time();
		$input['post_time'] = time();
		$input['post_gmt'] = $this->stack['static_var']['server_timezone'];
		$input['post_name'] = $input['post_is_page'] && NULL == $_POST['post_name'] ? urlencode($input['post_title']) : $input['post_name'];
		
		$postModel = $this->loadModel('posts');
		$insertId = $postModel->insertTable($input);
		$categoriesModel = $this->loadModel('categories');
		$categoriesModel->increaseFieldByKey($input['category_id'],'category_count');
		
		$trackback = 
		mgSendTrackback($url,array("title" => $input['post_title'],
								   "url"   => $this->stack['static_var']['index'].'/archives/'.$insertId.'/',
								   "excerpt" => $input['post_content'],
								   "blog_name" => $this->stack['static_var']['blog_name'],
								   "agent" => $this->stack['static_var']['version']));
		
		$staticModel = $this->loadModel('statics');
		$staticModel->increaseValueByName('count_posts');
		$this->deleteCache('static_var');
		
		if($input['post_tags'])
		{
			$tagsModel = $this->loadModel('tags');
			$tagsModel->insertTags($insertId,$input['post_tags']);
		}
		
		$this->result['open'] = true;
		$this->result['trackback'] = $trackback;
		$this->result['word'] = '文章 "'.$input['post_title'].'" 已经成功提交';
	}
	
	public function updatePost()
	{
		$input = $_POST;
		$input['post_is_draft'] = isset($_POST['post_is_draft']) ? $_POST['post_is_draft'] : 0;
		$this->requirePost(NULL,!$input['post_is_draft']);
		$this->requireGet('post_id');
		
		$url = isset($_POST['post_trackback']) ? $_POST['post_trackback'] : NULL;
		unset($input["post_trackback"]);
		$input['post_title'] = isset($_POST['post_title']) && $_POST['post_title'] ? $_POST['post_title'] : ($input['post_is_draft'] ? '无标题文档' : NULL);
		$input['post_content'] = isset($_POST['post_content'])  && $_POST['post_content'] ? $_POST['post_content'] : NULL;
		$input['post_allow_ping'] = isset($_POST['post_allow_ping']) ? $_POST['post_allow_ping'] : 0;
		$input['post_allow_comment'] = isset($_POST['post_allow_comment']) ? $_POST['post_allow_comment'] : 0;
		$input['post_allow_feed'] = isset($_POST['post_allow_feed']) ? $_POST['post_allow_feed'] : 0;
		$input['post_is_draft'] = isset($_POST['post_is_draft']) ? $_POST['post_is_draft'] : 0;
		$input['post_is_hidden'] = isset($_POST['post_is_hidden']) ? $_POST['post_is_hidden'] : 0;
		$input['post_is_page'] = isset($_POST['post_is_page']) ? $_POST['post_is_page'] : 0;
		$input['post_edit_time'] = time();
		$input['post_name'] = $input['post_is_page'] && NULL == $_POST['post_name'] ? urlencode($input['post_title']) : $input['post_name'];
		
		$postModel = $this->loadModel('posts');
		$post = $postModel->fectchOneByKey($_GET['post_id']);
		$trackback = 
		mgSendTrackback($url,array("title" => $input['post_title'],
								   "url"   => $this->stack['static_var']['index'].'/archives/'.$_GET['post_id'].'/',
								   "excerpt" => $input['post_content'],
								   "blog_name" => $this->stack['static_var']['blog_name'],
								   "agent" => $this->stack['static_var']['version']));
		
		if($post['category_id'] != $input['category_id'])
		{
			$categoriesModel = $this->loadModel('categories');
			$categoriesModel->increaseFieldByKey($input['category_id'],'category_count');
			$categoriesModel->decreaseFieldByKey($post['category_id'],'category_count');
		}
		$postModel->updateByKey($_GET['post_id'],$input);
		
		if($input['post_tags'])
		{
			$tagsModel = $this->loadModel('tags');
			$tagsModel->deleteTagsByPostId($_GET['post_id']);
			$tagsModel->insertTags($_GET['post_id'],$input['post_tags']);
		}
		
		$this->result['open'] = true;
		$this->result['trackback'] = $trackback;
		$this->result['word'] = '文章 "'.$post['post_title'].'" 已经被更新';
	}
	
	public function deletePost()
	{
		$this->requireGet('post_id');
		$postModel = $this->loadModel('posts');
		$commentsModel = $this->loadModel('comments');
		$categoriesModel = $this->loadModel('categories');
		
		$select = is_array($_GET['post_id']) ? $_GET['post_id'] : array($_GET['post_id']);
		foreach($select as $id)
		{
			$post = $postModel->fectchOneByKey($id);
			$postModel->deleteByKeys($id);
			$commentsModel->deleteByFieldEqual('post_id',$id);
			$categoriesModel->decreaseFieldByKey($post['category_id'],'category_count');
			if($post['post_tags'])
			{
				$tagsModel = $this->loadModel('tags');
				$tagsModel->deleteTagsByPostId($id);
			}
		}
		
		$staticModel = $this->loadModel('statics');
		$staticModel->decreaseValueByName('count_posts',count($select));
		$this->deleteCache('static_var');
		
		$this->result['open'] = true;
		$this->result['word'] = (count($select) > 1 ? '您的多篇文章' : '文章 "'.$post['post_title'].'" ').'已经被删除';
	}
	
	public function runModule()
	{
		$this->onGet("do","updatePost","update");
		$this->onGet("do","insertPost","insert");
		$this->onGet("do","deletePost","del");
		return $this->result;
	}
}
?>
