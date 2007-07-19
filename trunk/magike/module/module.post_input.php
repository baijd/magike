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
	
	public function insertPost($postInput = NULL)
	{
		
		$input = $postInput ? $postInput : $_POST;
		$input['post_is_draft'] = isset($input['post_is_draft']) && $input['post_is_draft'] ? $input['post_is_draft'] : 0;
		
		if(!$postInput)
		{
			$this->requirePost(NULL,!$input['post_is_draft']);
		}
		
		$url = isset($input['post_trackback']) ? $input['post_trackback'] : NULL;
		unset($input["post_trackback"]);
		unset($input["post_id"]);
		$input['post_title'] = isset($input['post_title']) && $input['post_title'] ? trim($input['post_title']) : ($input['post_is_draft'] ? '无标题文档' : NULL);
		$input['post_content'] = isset($input['post_content'])  && $input['post_content'] ? $input['post_content'] : NULL;
		$input['post_allow_ping'] = isset($input['post_allow_ping']) && $input['post_allow_ping'] ? $input['post_allow_ping'] : 0;
		$input['post_allow_comment'] = isset($input['post_allow_comment']) && $input['post_allow_comment'] ? $input['post_allow_comment'] : 0;
		$input['post_allow_feed'] = isset($input['post_allow_feed']) && $input['post_allow_feed'] ? $input['post_allow_feed'] : 0;
		$input['post_is_hidden'] = isset($input['post_is_hidden']) && $input['post_is_hidden'] ? $input['post_is_hidden'] : 0;
		$input['post_is_page'] = isset($input['post_is_page']) && $input['post_is_page'] ? $input['post_is_page'] : 0;
		$input['category_id'] = isset($input['category_id']) && $input['category_id'] ? $input['category_id'] : 0;
		
		if(!isset($input['post_edit_time']) || !$input['post_edit_time'])
		{
			$input['post_edit_time'] = time() - $this->stack['static_var']['server_timezone'];
		}
		if(!isset($input['post_time']) || !$input['post_time'])
		{
			$input['post_time'] = time() - $this->stack['static_var']['server_timezone'];
		}
		
		$input['post_name'] = $input['post_is_page'] && NULL == $input['post_name'] ? str_replace('%','',urlencode($input['post_title'])) : $input['post_name'];
		
		//自动生成密码
		$autoPassword = NULL;
		if($input['post_is_hidden'] && NULL == $input['post_password'])
		{
			$input['post_password'] = mgCreateRandomString(7);
			$autoPassword = ',密码为<b>'.$input['post_password'].'</b>.';
		}
		
		if(NULL != $input['post_password'])
		{
			$input['post_is_hidden'] = 1;
		}
		
		$postModel = $this->loadModel('posts');
		$insertId = $postModel->insertTable($input);
		if(!$input['post_is_page'] && !$input['post_is_hidden'])
		{
			$categoriesModel = $this->loadModel('categories');
			$categoriesModel->increaseFieldByKey($input['category_id'],'category_count');
		}
		
		$trackback = 
		mgSendTrackback($url,array("title" => $input['post_title'],
							"url"   => $this->stack['static_var']['index'].'/archives/'.$insertId.'/',
							"excerpt" => $input['post_content'],
							"blog_name" => $this->stack['static_var']['blog_name'],
							"agent" => $this->stack['static_var']['version']));
		
		$staticModel = $this->loadModel('statics');
		$staticModel->increaseValueByName('count_posts');
		$this->deleteCache('static_var');
		
		if(isset($input['post_tags']) && $input['post_tags'])
		{
			$tagsModel = $this->loadModel('tags');
			$tagsModel->insertTags($insertId,$input['post_tags']);
			$tags = $tagsModel->getTags($input['post_tags']);
			foreach($tags as $key => $val)
			{
				$tagsModel->increaseFieldByKey($key,'tag_count');
			}
		}
		
		$this->result['open'] = true;
		$this->result['trackback'] = $trackback;
		$this->result['insert_id'] = $insertId;
		$this->result['time'] = date("H点i分");
		$this->result['word'] = '文章 "'.$input['post_title'].'" 已经成功提交'.$autoPassword;
		
		return $insertId;
	}
	
	public function updatePost($postInput = NULL,$postId = 0)
	{
		$input = $postInput ? $postInput : $_POST;
		$input['post_is_draft'] = isset($input['post_is_draft']) && $input['post_is_draft'] ? $input['post_is_draft'] : 0;
		$postId = $postId ? $postId : $_GET['post_id'];
		
		if(!$postInput)
		{
			$this->requirePost(NULL,!$input['post_is_draft']);
			$this->requireGet('post_id');
		}
		
		$url = isset($input['post_trackback']) ? $input['post_trackback'] : NULL;
		unset($input["post_trackback"]);
		unset($input["post_id"]);
		$input['post_title'] = isset($input['post_title']) && $input['post_title'] ? trim($input['post_title']) : ($input['post_is_draft'] ? '无标题文档' : NULL);
		$input['post_content'] = isset($input['post_content'])  && $input['post_content'] ? $input['post_content'] : NULL;
		$input['post_tags'] = isset($input['post_tags'])  && $input['post_tags'] ? $input['post_tags'] : NULL;
		$input['post_allow_ping'] = isset($input['post_allow_ping']) && $input['post_allow_ping'] ? $input['post_allow_ping'] : 0;
		$input['post_allow_comment'] = isset($input['post_allow_comment']) && $input['post_allow_comment'] ? $input['post_allow_comment'] : 0;
		$input['post_allow_feed'] = isset($input['post_allow_feed']) && $input['post_allow_feed'] ? $input['post_allow_feed'] : 0;
		$input['post_is_hidden'] = isset($input['post_is_hidden']) && $input['post_is_hidden'] ? $input['post_is_hidden'] : 0;
		$input['post_is_page'] = isset($input['post_is_page']) && $input['post_is_page'] ? $input['post_is_page'] : 0;
		$input['post_is_draft'] = isset($input['post_is_draft']) && $input['post_is_draft'] ? $input['post_is_draft'] : 0;
		$input['category_id'] = isset($input['category_id']) && $input['category_id'] ? $input['category_id'] : 0;
		
		if(!isset($input['post_edit_time']) || !$input['post_edit_time'])
		{
			$input['post_edit_time'] = time() - $this->stack['static_var']['server_timezone'];
		}
		if(!isset($input['post_time']) || !$input['post_time'])
		{
			$input['post_time'] = time() - $this->stack['static_var']['server_timezone'];
		}
		
		$input['post_name'] = $input['post_is_page'] && NULL == $input['post_name'] ? str_replace('%','',urlencode($input['post_title'])) : $input['post_name'];
		
		//自动生成密码
		$autoPassword = NULL;
		if($input['post_is_hidden'] && NULL == $input['post_password'])
		{
			$input['post_password'] = mgCreateRandomString(7);
			$autoPassword = ',密码为<b>'.$input['post_password'].'</b>.';
		}
		
		if(NULL != $input['post_password'])
		{
			$input['post_is_hidden'] = 1;
		}
		
		$postModel = $this->loadModel('posts');
		$post = $postModel->fetchOneByKey($postId);
		if(!$post)
		{
			return false;
		}

		$trackback = 
		mgSendTrackback($url,array("title" => $input['post_title'],
								   "url"   => $this->stack['static_var']['index'].'/archives/'.$postId.'/',
								   "excerpt" => $input['post_content'],
								   "blog_name" => $this->stack['static_var']['blog_name'],
								   "agent" => $this->stack['static_var']['version']));
		
		$categoriesModel = $this->loadModel('categories');
		if($post['category_id'] != $input['category_id'] && !$post['post_is_page'] && !$post['post_is_hidden'])
		{
			$categoriesModel->decreaseFieldByKey($post['category_id'],'category_count');
		}
		if($post['category_id'] != $input['category_id'] && !$input['post_is_page'] && !$input['post_is_hidden'])
		{
			$categoriesModel->increaseFieldByKey($input['category_id'],'category_count');
		}
		
		$updated = $postModel->updateByKey($postId,$input);
		

		$tagsModel = $this->loadModel('tags');
		
		if($post['post_tags'])
		{
			$tagsModel->deleteTagsByPostId($postId);
			$tags = $tagsModel->getTags($post['post_tags']);
			foreach($tags as $key => $val)
			{
				$tagsModel->decreaseFieldByKey($key,'tag_count');
			}
		}
		
		if(isset($input['post_tags']) && $input['post_tags'])
		{
			$tagsModel->insertTags($postId,$input['post_tags']);
			$tags = $tagsModel->getTags($input['post_tags']);
			foreach($tags as $key => $val)
			{
				$tagsModel->increaseFieldByKey($key,'tag_count');
			}
		}
		
		$this->result['open'] = true;
		$this->result['trackback'] = $trackback;
		$this->result['time'] = date("H点i分");
		$this->result['word'] = '文章 "'.$post['post_title'].'" 已经被更新'.$autoPassword;
		
		return $updated;
	}
	
	public function deletePost($postInput = NULL)
	{
		if(!$postInput)
		{
			$this->requireGet('post_id');
		}
		$postModel = $this->loadModel('posts');
		$commentsModel = $this->loadModel('comments');
		$categoriesModel = $this->loadModel('categories');
		
		if($postInput)
		{
			$select = is_array($postInput) ? $postInput : array($postInput);
		}
		else
		{
			$select = is_array($_GET['post_id']) ? $_GET['post_id'] : array($_GET['post_id']);
		}
		
		$deleted = true;
		foreach($select as $id)
		{
			$post = $postModel->fetchOneByKey($id);
			$deleted &= $postModel->deleteByKeys($id);
			if(!$post)
			{
				continue;
			}
			
			$commentsModel->deleteByFieldEqual('post_id',$id);
			if(!$post['post_is_page'] && !$post['post_is_hidden'])
			{
				$categoriesModel->decreaseFieldByKey($post['category_id'],'category_count');
			}
			if($post['post_tags'])
			{
				$tagsModel = $this->loadModel('tags');
				$tagsModel->deleteTagsByPostId($id);
				$tags = $tagsModel->getTags($post['post_tags']);
				foreach($tags as $key => $val)
				{
					$tagsModel->decreaseFieldByKey($key,'tag_count');
				}
			}
		}
		
		$staticModel = $this->loadModel('statics');
		$staticModel->decreaseValueByName('count_posts',count($select));
		$this->deleteCache('static_var');
		
		$this->result['open'] = true;
		$this->result['word'] = $deleted ? (count($select) > 1 ? '您的多篇文章' : '文章 "'.$post['post_title'].'" ').'已经被删除' : '删除失败';
		return $deleted;
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
