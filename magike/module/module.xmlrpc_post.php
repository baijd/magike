<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.xmlrpc_post.php
 * Copyright : Reload from wordpress,by Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
 class XmlrpcPost extends PostInput
 {
	private $xmlrpcServer;
	private $methods;
	private $error;
	
	function __construct()
	{
		parent::__construct();

		$this->methods = array(
			// WordPress API
			'wp.getPage'			=> array($this,'wpGetPage'),
			'wp.getPages'			=> array($this,'wpGetPages'),
			'wp.newPage'			=> array($this,'wpNewPage'),
			'wp.deletePage'			=> array($this,'deletePage'),
			'wp.editPage'			=> array($this,'wpEditPage'),
			'wp.getPageList'			=> array($this,'wpGetPageList'),
			'wp.getAuthors'			=> array($this,'wpGetAuthors'),
			'wp.getCategories'		=> array($this,'mwGetCategories'),
			'wp.newCategory'			=> array($this,'wpNewCategory'),
			'wp.suggestCategories'		=> array($this,'wpSuggestCategories'),
			'wp.uploadFile'			=> array($this,'mwNewMediaObject'),

			// Blogger API
			'blogger.getUsersBlogs' => array($this,'bloggerGetUsersBlogs'),
			'blogger.getUserInfo'    => array($this,'bloggerGetUserInfo'),
			'blogger.getPost' 	       => array($this,'bloggerGetPost'),
			'blogger.getRecentPosts' => array($this,'bloggerGetRecentPosts'),
			'blogger.getTemplate' => array($this,'bloggerGetTemplate'),
			'blogger.setTemplate' => array($this,'bloggerSetTemplate'),
			'blogger.deletePost' => array($this,'bloggerDeletePost'),

			// MetaWeblog API (with MT extensions to structs)
			'metaWeblog.newPost' => array($this,'mwNewPost'),
			'metaWeblog.editPost' => array($this,'mwEditPost'),
			'metaWeblog.getPost' => array($this,'mwGetPost'),
			'metaWeblog.getRecentPosts' => array($this,'mwGetRecentPosts'),
			'metaWeblog.getCategories' => array($this,'mwGetCategories'),
			'metaWeblog.newMediaObject' => array($this,'mwNewMediaObject'),

			// MetaWeblog API aliases for Blogger API
			'metaWeblog.deletePost' => array($this,'bloggerDeletePost'),
			'metaWeblog.getTemplate' => array($this,'bloggerGetTemplate'),
			'metaWeblog.setTemplate' => array($this,'bloggerSetTemplate'),
			'metaWeblog.getUsersBlogs' => array($this,'bloggerGetUsersBlogs'),

			// MovableType API
			'mt.getCategoryList' => array($this,'mtGetCategoryList'),
			'mt.getRecentPostTitles' => array($this,'mtGetRecentPostTitles'),
			'mt.getPostCategories' => array($this,'mtGetPostCategories'),
			'mt.setPostCategories' => array($this,'mtSetPostCategories'),

			// PingBack
			'pingback.ping' => array($this,'pingbackPing'),
			'pingback.extensions.getPingbacks' => array($this,'pingbackExtensionsGetPingbacks')
		);
	}
	
	private function checkAccess($userName,$password)
	{
		$userModel = $this->loadModel('users');
		$groupModel = $this->loadModel('groups');
		$pathModel = $this->loadModel('paths');
		$user = $userModel->fetchOne(array('table' => 'table.users',
								   'where' => array('template' => 'user_name = ? AND user_password = ?',
											  'value' => array($userName,
														md5($password)
														))
										  	  ));
		if($user)
		{
			$group = $groupModel->getUserGroups($user['id']);
			$userGroup = array();
			foreach($group as $val)
			{
				$userGroup[] = $val['group_id'];
			}
			
			if($pathModel->checkPathAccess($userGroup,'/admin/posts/all/'))
			{
				return $user;
			}
			else
			{
				$this->error = new IXR_Error(403, '权限不足.');
				return false;
			}
		}
		else
		{
			$this->error = new IXR_Error(403, '无法登陆,密码错误.');
			return false;
		}
	}
	
	private function getPostExtended($content)
	{
		$post = explode("<!--more-->",$content,2);
		return array($post[0],isset($post[1]) ? $post[1] : NULL);
	}
	
	private function praseBloggerContent($content)
	{
		$result = array();
		preg_match("/\<title\>\s*(.+?)\<\/title\>/is",$content,$out);
		$result['title'] = $out[1];
		$content = str_replace("<title>{$result['title']}</title>","",$content);
		
		preg_match("/\<category\>\s*(.+?)\<\/category\>/is",$content,$out);
		$result['category'] = $out[1];
		$content = str_replace("<category>{$result['category']}</category>","",$content);
		
		$result["content"] = $content;
		return $result;
	}
	
	public function wpGetPage($args)
	{
		$blogId	= intval($args[0]);
		$pageId	= intval($args[1]);
		$userName	= $args[2];
		$password	= $args[3];
		
		if(!$this->checkAccess($userName, $password)) 
		{
			return($this->result);
		}
		
		$postModel = $this->loadModel('posts');
		$page = $postModel->fetchPostById($pageId);
		$content = $this->getPostExtended($page['post_content']);
		$userModel = $this->loadModel('users');
		$author = $userModel->fetchOneByKey($page['user_id']);
		
		if($page && $page['post_is_page'])
		{
			$link = $this->stack['static_var']['index'].'/'.$page["post_name"].'.html';
			$pageStruct = array(
				"dateCreated"			=> new IXR_Date($this->stack['static_var']['time_zone']+$page["post_time"]),
				"userid"				=> $page["user_id"],
				"page_id"				=> $page["post_id"],
				"page_status"			=> $page["post_is_draft"] ? "draft" : "public",
				"description"			=> $content[0],
				"title"					=> $page["post_title"],
				"link"					=> $link,
				"permaLink"				=> $link,
				"categories"			=> array($page["category_name"]),
				"excerpt"				=> NULL,
				"text_more"				=> $content[1],
				"mt_allow_comments"		=> $page['post_allow_comment'],
				"mt_allow_pings"			=> $page['post_allow_ping'],
				"wp_slug"				=> $page['post_name'],
				"wp_password"			=> $page['post_password'],
				"wp_author"			=> $author['user_name'],
				"wp_page_parent_id"		=> 0,
				"wp_page_parent_title"		=> NULL,
				"wp_page_order"			=> $page['post_id'],
				"wp_author_id"			=> $page['user_id'],
				"wp_author_display_name"	=> $page['post_user_name']
			);
			
			return $pageStruct;
		}
		else
		{
			return(new IXR_Error(404, "对不起,不存在此页面."));
		}
	}
	
	public function wpGetPages($args)
	{
		$blogId	= intval($args[0]);
		$userName	= $args[1];
		$password	= $args[2];
		
		if(!$this->checkAccess($userName, $password)) 
		{
			return($this->error);
		}
		
		$postModel = $this->loadModel('posts');
		$pages = $postModel->listAllPagesIncludeHidden();
		$pagesStruct = array();
		
		foreach($pages as $page)
		{
			$link = $this->stack['static_var']['index'].'/'.$page["post_name"].'.html';
			$content = $this->getPostExtended($page['post_content']);
			$pagesStruct[] = array(
				"dateCreated"			=> new IXR_Date($this->stack['static_var']['time_zone']+$page["post_time"]),
				"userid"				=> $page["user_id"],
				"page_id"				=> $page["post_id"],
				"page_status"			=> $page["post_is_draft"] ? "draft" : "public",
				"description"			=> $content[0],
				"title"					=> $page["post_title"],
				"link"					=> $link,
				"permaLink"				=> $link,
				"categories"			=> array($page["category_name"]),
				"excerpt"				=> NULL,
				"text_more"				=> $content[1],
				"mt_allow_comments"		=> $page['post_allow_comment'],
				"mt_allow_pings"			=> $page['post_allow_ping'],
				"wp_slug"				=> $page['post_name'],
				"wp_password"			=> $page['post_password'],
				"wp_author"			=> $author['user_name'],
				"wp_page_parent_id"		=> 0,
				"wp_page_parent_title"		=> NULL,
				"wp_page_order"			=> $page['post_id'],
				"wp_author_id"			=> $page['user_id'],
				"wp_author_display_name"	=> $page['post_user_name']
			);
		}
		
		return $pagesStruct;
	}
	
	public function wpNewPage($args)
	{
		$userName	= $args[1];
		$password	= $args[2];
		$page	= $args[3];
		$publish	= $args[4];
		
		if(!$this->checkAccess($userName, $password)) 
		{
			return($this->error);
		}
		
		$args[3]["post_type"] = "page";
		return($this->mwNewPost($args));
	}
	
	public function wpDeletePage($args)
	{
		$blogId	= intval($args[0]);
		$pageId	= intval($args[3]);
		$userName	= $args[1];
		$password	= $args[2];
		
		if(!$this->checkAccess($userName, $password)) 
		{
			return($this->error);
		}
		
		$result = $this->deletePost($pageId);
		if(!$result)
		{
			return(new IXR_Error(500,"无法删除页面."));
		}
		
		return true;
	}
	
	public function wpEditPage($args)
	{
		$blogId	= intval($args[0]);
		$pageId	= intval($args[1]);
		$userName	= $args[2];
		$password	= $args[3];
		$content	= $args[4];
		$publish	= $args[5];
		
		$content["post_type"] = "page";
		$args = array(
			$page_id,
			$username,
			$password,
			$content,
			$publish
		);
		
		return $this->mwEditPost($args);
	}
	
	public function wpGetPageList($args)
	{
		$blogId	= intval($args[0]);
		$userName	= $args[1];
		$password	= $args[2];
		
		if(!$this->checkAccess($userName, $password)) 
		{
			return($this->error);
		}
		
		$postModel = $this->loadModel('posts');
		$pages = $postModel->listAllPagesIncludeHidden();
		$pagesStruct = array();
		
		foreach($pages as $page)
		{
			$pagesStruct[] = array(
				"dateCreated"	=> new IXR_Date($this->stack['static_var']['time_zone']+$page["post_time"]),
				"page_id"		=> $page["post_id"],
				"page_title"		=> $page["post_title"],
				"page_parent_id"	=> 0
			);
		}
		
		return $pagesStruct;
	}
	
	public function wpGetAuthors($args)
	{		
		$blogId	= intval($args[0]);
		$userName	= $args[1];
		$password	= $args[2];
		
		$result = $this->checkAccess($userName, $password);
		if(!$result)
		{
			return($this->error);
		}
		
		switch($this->stack['static_var']['write_default_name'])
		{
			case "username":
				$displayName = $result["user_name"];
			case "nickname":
				$displayName = $result["user_nick"];
			case "firstname":
				$displayName = $result["user_firstname"]." ".$result["user_lastname"];
			case "lastname":
				$displayName = $result["user_lastname"]." ".$result["user_firstname"];
			default:
				$displayName = $result["user_name"];
				break;
		}
		
		$struct = array("user_id" => $result["id"],
				      "user_login" => $result["user_name"],
				      "display_name" => $displayName,
				      "user_email"	=> $result["user_mail"],
				      "meta_value" => "");
		return array($struct);
	}
	
	public function wpNewCategory($args)
	{
		$blogId	= intval($args[0]);
		$userName	= $args[1];
		$password	= $args[2];
		$category	= $args[3];
		
		if(!$this->checkAccess($userName, $password)) 
		{
			return($this->error);
		}
		
		$categoryModel = $this->loadModel('categories');
		$item = $categoryModel->fetchOne(array('fields'=> 'MAX(category_sort) AS max_sort',
													  'table' => 'table.categories'));
													  
		$input['category_name'] = $category['name'];
		$input['category_postname'] = isset($category['slug']) ? urlencode($category['slug']) : urlencode($category['name']);
		$input['category_describe'] = isset($category['description']) ? $category['description'] : $category['name'];
		$input['category_count'] = 0;
		$input['category_sort'] = $item['max_sort']+1;
		
		if(!($insertId = $categoryModel->insertTable($input)))
		{
			return new IXR_Error(500, "对不起,无法增加分类.");
		}
		
		return $insertId;
	}
	
	public function wpSuggestCategories($args)
	{
		$blogId		= intval($args[0]);
		$userName		= $args[1];
		$password		= $args[2];
		$category		= $args[3];
		$maxResults	= intval($args[4]) ? false : intval($args[4]);
		
		if(!$this->checkAccess($userName, $password)) 
		{
			return($this->error);
		}
		
		$categoryModel = $this->loadModel('categories');
		$categories = $categoryModel->fetchByFieldLike('category_name',"{$category}%",0,$maxResults);
		return $categories;
	}
	
	public function bloggerGetUsersBlogs($args)
	{
		$blogId		= intval($args[0]);
		$userName		= $args[1];
		$password		= $args[2];
		
		$result = $this->checkAccess($userName, $password);
		if(!$result)
		{
			return($this->error);
		}
		
		$struct = array(
			'isAdmin' => true,
			'url'	    => $this->stack['static_var']['siteurl'],
			'blogid'   => 1,
			'blogName' => $this->stack['static_var']['blog_name']
		);
		
		return array($struct);
	}
	
	public function bloggerGetUserInfo($args)
	{
		$blogId		= intval($args[0]);
		$userName		= $args[1];
		$password		= $args[2];
		
		$result = $this->checkAccess($userName, $password);
		if(!$result)
		{
			return($this->error);
		}
		
		$struct = array(
			'nickname'  => $result['user_nick'],
			'userid'    => $result['id'],
			'url'       => $result['user_url'],
			'email'     => $result['user_mail'],
			'lastname'  => $result['user_lastname'],
			'firstname' => $result['user_firstname']
		);
		
		return $struct;
	}
	
	public function bloggerGetPost($args)
	{
		$blogId	= intval($args[0]);
		$postId	= intval($args[1]);
		$userName	= $args[2];
		$password	= $args[3];
		
		if(!$this->checkAccess($userName, $password)) 
		{
			return($this->error);
		}
		
		$postModel = $this->loadModel('posts');
		$post = $postModel->fetchPostById($postId);
		
		$content  = '<title>'.$post['post_title'].'</title>';
		$content .= '<category>'.$post['category_name'].'</category>';
		$content .= stripslashes($post['post_content']);
		
		$struct = array(
			'userid'    => $post['user_id'],
			'dateCreated' => new IXR_Date(date('Ymd\TH:i:s', $this->stack['static_var']['time_zone']+$post['post_time'])),
			'content'     => $content,
			'postid'  => $post['post_id']
		);

		return $struct;
	}
	
	public function bloggerGetRecentPosts($args)
	{
		$blogId	= intval($args[0]);
		$userName	= $args[1];
		$password	= $args[2];
		$postsNum	= intval($args[3]);
		
		if(!$this->checkAccess($userName, $password)) 
		{
			return($this->error);
		}
		
		$postModel = $this->loadModel('posts');
		$posts = $postModel->listAllPosts($postsNum,0);
		

		if (!$posts_list) {
			$this->error = new IXR_Error(500, '没有任何文章.');
			return $this->error;
		}
		
		$struct = array();
		foreach($posts as $post)
		{
			$content  = '<title>'.$post['post_title'].'</title>';
			$content .= '<category>'.$post['category_name'].'</category>';
			$content .= stripslashes($post['post_content']);
			$struct[] = array(
			'userid'    => $post['user_id'],
			'dateCreated' => new IXR_Date(date('Ymd\TH:i:s', $this->stack['static_var']['time_zone']+$post['post_time'])),
			'content'     => $content,
			'postid'  => $post['post_id']
			);
		}
		
		return $struct;
	}
	
	public function bloggerGetTemplate($args)
	{
		$blogId    = intval($args[1]);
		$userName = $args[2];
		$password  = $args[3];
		$template   = $args[4];
		
		if(!$this->checkAccess($userName, $password)) 
		{
			return($this->error);
		}
		
		$filename = $this->stack['static_var']['siteurl']. '/';
		$filename = preg_replace('#https?://.+?/#', $_SERVER['DOCUMENT_ROOT'].'/', $filename);

		$f = fopen($filename, 'r');
		$content = fread($f, filesize($filename));
		fclose($f);
		
		return $content;
	}
	
	public function bloggerSetTemplate($args)
	{
		$blogId    = intval($args[1]);
		$userName = $args[2];
		$password  = $args[3];
		$content  = $args[4];
		$template   = $args[5];
		
		if(!$this->checkAccess($userName, $password)) 
		{
			return($this->error);
		}
		
		return true;
	}
	
	public function bloggerDeletePost($args)
	{
		$postId    = intval($args[1]);
		$userName = $args[2];
		$password  = $args[3];
		$publish   = $args[4];
		
		if(!$this->checkAccess($userName, $password)) 
		{
			return($this->error);
		}
		
		$result = $this->deletePost($postId);
		
		if (!$result)
		{
			return new IXR_Error(500, '删除时出现错误.');
		}
		
		return true;
	}
	
	public function mwNewPost($args)
	{
		$blogId    = intval($args[0]);
		$userName = $args[1];
		$password  = $args[2];
		$content  = $args[3];
		$publish   = $args[4];
		
		$result = $this->checkAccess($userName, $password);
		if(!$result) 
		{
			return($this->error);
		}
		
		$input["post_is_page"] = isset($content["post_type"]) && $content["post_type"] == "page" ? 1 : 0;
		$input["post_name"] = isset($content["wp_slug"]) ? $content["wp_slug"] : NULL;
		$input["post_password"] = isset($content["wp_password"]) ? $content["wp_password"] : NULL;
		$input["user_id"] = $result["id"];
		$input["post_title"] = $content['title'];
		$input["post_is_draft"] = !$publish;
		$input['post_allow_feed'] = 1;
		$input["post_content"] = isset($content['mt_text_more']) && $content['mt_text_more'] ? $content['description']."\n<!--more-->\n".$content['mt_text_more'] 
		: $content['description'];
		
		if(isset($content['categories']))
		{
			$categoryModel = $this->loadModel("categories");
			$categoryName = NULL;
			if (is_array($content['categories']))
			{
				foreach ($content['categories'] as $val)
				{
					$categoryName = $val;
				}
			}
			else
			{
				$categoryName = $content['categories'];
			}
			
			if($categoryName)
			{
				$category = $categoryModel->fetchOneByFieldEqual("category_name",$categoryName);
				$input["category_id"] = $category['id'];
			}
			else
			{
				$input["category_id"] = $this->stack['static_var']['write_default_category'];
			}
		}
		
		
		if(isset($content["mt_allow_comments"]))
		{
			if(!is_numeric($content["mt_allow_comments"]))
			{
				switch($content["mt_allow_comments"]) 
				{
					case "closed":
						$input["post_allow_comment"] = 0;
						break;
					case "open":
						$input["post_allow_comment"] = 1;
						break;
					default:
						$input["post_allow_comment"] = $this->stack['static_var']['default_allow_comment'];
						break;
				}
			}
			else
			{
				switch((int) $content["mt_allow_comments"])
				{
					case 0:
						$input["post_allow_comment"] = 0;
						break;
					case 1:
						$input["post_allow_comment"] = 1;
						break;
					default:
						$input["post_allow_comment"] =  $this->stack['static_var']['default_allow_comment'];
						break;
				}
			}
		}
		else 
		{
			$input["post_allow_comment"] = $this->stack['static_var']['default_allow_comment'];
		}
		
		if(isset($content["mt_allow_pings"]))
		{
			if(!is_numeric($content["mt_allow_pings"]))
			{
				switch($content["mt_allow_pings"]) 
				{
					case "closed":
						$input["post_allow_ping"] = 0;
						break;
					case "open":
						$input["post_allow_ping"] = 1;
						break;
					default:
						$input["post_allow_ping"] = $this->stack['static_var']['default_allow_ping'];
						break;
				}
			}
			else
			{
				switch((int) $content["mt_allow_pings"])
				{
					case 0:
						$input["post_allow_ping"] = 0;
						break;
					case 1:
						$input["post_allow_ping"] = 1;
						break;
					default:
						$input["post_allow_ping"] =  $this->stack['static_var']['default_allow_ping'];
						break;
				}
			}
		}
		else 
		{
			$input["post_allow_ping"] = $this->stack['static_var']['default_allow_ping'];
		}
		
		if(isset($content['dateCreated']))
		{
			$date = $content['dateCreated'];
			$input["post_time"] = $date->getTimestamp();
		}
		 
		 $insertId = $this->insertPost($input);
		 
		if (!$insertId) 
		{
			return new IXR_Error(500, '对不起,提交文章时发生错误.');
		}
		
		return strval($insertId);
	}
	
	public function mwEditPost($args)
	{
		$postId    = intval($args[0]);
		$userName = $args[1];
		$password  = $args[2];
		$content  = $args[3];
		$publish   = $args[4];
		
		$result = $this->checkAccess($userName, $password);
		if(!$result) 
		{
			return($this->error);
		}
		
		$input["post_is_page"] = isset($content["post_type"]) && ($content["post_type"] == "page") ? 1 : 0;
		$input["post_name"] = isset($content["wp_slug"]) ? $content["wp_slug"] : NULL;
		$input["post_password"] = isset($content["wp_password"]) ? $content["wp_password"] : NULL;
		$input["user_id"] = $result["id"];
		$input["post_title"] = $content['title'];
		$input["post_is_draft"] = !$publish;
		$input['post_allow_feed'] = 1;
		$input["post_content"] = isset($content['mt_text_more']) && $content['mt_text_more'] ? $content['description']."\n<!--more-->\n".$content['mt_text_more'] 
		: $content['description'];
		
		if(isset($content['categories']))
		{
			$categoryModel = $this->loadModel("categories");
			$categoryName = NULL;
			if (is_array($content['categories']))
			{
				foreach ($content['categories'] as $val)
				{
					$categoryName = $val;
				}
			}
			else
			{
				$categoryName = $content['categories'];
			}
			
			if($categoryName)
			{
				$category = $categoryModel->fetchOneByFieldEqual("category_name",$categoryName);
				$input["category_id"] = $category['id'];
			}
			else
			{
				$input["category_id"] = $this->stack['static_var']['write_default_category'];
			}
		}
		
		if(isset($content["mt_allow_comments"]))
		{
			if(!is_numeric($content["mt_allow_comments"]))
			{
				switch($content["mt_allow_comments"]) 
				{
					case "closed":
						$input["post_allow_comment"] = 0;
						break;
					case "open":
						$input["post_allow_comment"] = 1;
						break;
					default:
						$input["post_allow_comment"] = $this->stack['static_var']['default_allow_comment'];
						break;
				}
			}
			else
			{
				switch((int) $content["mt_allow_comments"])
				{
					case 0:
						$input["post_allow_comment"] = 0;
						break;
					case 1:
						$input["post_allow_comment"] = 1;
						break;
					default:
						$input["post_allow_comment"] =  $this->stack['static_var']['default_allow_comment'];
						break;
				}
			}
		}
		else 
		{
			$input["post_allow_comment"] = $this->stack['static_var']['default_allow_comment'];
		}
		
		if(isset($content["mt_allow_pings"]))
		{
			if(!is_numeric($content["mt_allow_pings"]))
			{
				switch($content["mt_allow_pings"]) 
				{
					case "closed":
						$input["post_allow_ping"] = 0;
						break;
					case "open":
						$input["post_allow_ping"] = 1;
						break;
					default:
						$input["post_allow_ping"] = $this->stack['static_var']['default_allow_ping'];
						break;
				}
			}
			else
			{
				switch((int) $content["mt_allow_pings"])
				{
					case 0:
						$input["post_allow_ping"] = 0;
						break;
					case 1:
						$input["post_allow_ping"] = 1;
						break;
					default:
						$input["post_allow_ping"] =  $this->stack['static_var']['default_allow_ping'];
						break;
				}
			}
		}
		else 
		{
			$input["post_allow_ping"] = $this->stack['static_var']['default_allow_ping'];
		}
		
		if(isset($content['dateCreated']))
		{
			$date = $content['dateCreated'];
			$input["post_time"] = $date->getTimestamp();
		}
		 
		 $updated = $this->updatePost($input,$postId);
		 
		if (!$updated) 
		{
			return new IXR_Error(500, '对不起,更新文章时发生错误.');
		}
		
		return strval($updated);
	}
	
	public function mwGetPost($args)
	{
		$postId	= intval($args[0]);
		$userName	= $args[1];
		$password	= $args[2];
		
		if(!$this->checkAccess($userName, $password)) 
		{
			return($this->result);
		}
		
		$postModel = $this->loadModel('posts');
		$post = $postModel->fetchPostById($postId);
		$content = $this->getPostExtended($post['post_content']);
		$userModel = $this->loadModel('users');
		$author = $userModel->fetchOneByKey($post['user_id']);
		
		if($post && !$post['post_is_page'])
		{
			$link = $post['post_name'] ? $this->stack['static_var']['index'].'/'.$post["post_name"].'.html' : $this->stack['static_var']['index'].'/archives/'.$post["post_id"].'/';
			$pageStruct = array(
				"dateCreated"			=> new IXR_Date($this->stack['static_var']['time_zone']+$post["post_time"]),
				"userid"				=> $post["user_id"],
				"postid"				=> $post["post_id"],
				"description"			=> $content[0],
				"title"					=> $post["post_title"],
				"link"					=> $link,
				"permaLink"				=> $link,
				"categories"			=> array($post["category_name"]),
				"mt_excerpt"			=> NULL,
				"mt_text_more"			=> $content[1],
				"mt_allow_comments"		=> $post['post_allow_comment'],
				"mt_allow_pings"			=> $post['post_allow_ping'],
				"wp_slug"				=> $post['post_name'],
				"wp_password"			=> $post['post_password'],
				"wp_author_id"			=> $post['user_id'],
				"wp_author_display_name"	=> $post['post_user_name']
			);
			
			return $pageStruct;
		}
		else
		{
			return(new IXR_Error(404, "对不起,不存在此文章."));
		}
	}
	
	public function mwGetRecentPosts($args)
	{
		$blogId	= intval($args[0]);
		$userName	= $args[1];
		$password	= $args[2];
		$postsNum   = intval($args[3]);
		
		if(!$this->checkAccess($userName, $password)) 
		{
			return($this->error);
		}
		
		$postModel = $this->loadModel('posts');
		$posts = $postModel->listAllPostsIncludeHidden($postsNum,0);
		$postsStruct = array();
		
		foreach($posts as $post)
		{
			$content = $this->getPostExtended($post['post_content']);
			$link = $post['post_name'] ? $this->stack['static_var']['index'].'/'.$post["post_name"].'.html' : $this->stack['static_var']['index'].'/archives/'.$post["post_id"].'/';
			$postsStruct[] = array(
				"dateCreated"			=> new IXR_Date($this->stack['static_var']['time_zone']+$post["post_time"]),
				"userid"				=> $post["user_id"],
				"postid"				=> $post["post_id"],
				"description"			=> $content[0],
				"title"					=> $post["post_title"],
				"link"					=> $link,
				"permaLink"				=> $link,
				"categories"			=> array($post["category_name"]),
				"mt_excerpt"			=> NULL,
				"mt_text_more"			=> $content[1],
				"mt_allow_comments"		=> $post['post_allow_comment'],
				"mt_allow_pings"			=> $post['post_allow_ping'],
				"wp_slug"				=> $post['post_name'],
				"wp_password"			=> $post['post_password'],
				"wp_author_id"			=> $post['user_id'],
				"wp_author_display_name"	=> $post['post_user_name']
			);
		}
		
		return $postsStruct;
	}
	
	public function mwGetCategories($args)
	{
		$blogId		= intval($args[0]);
		$userName		= $args[1];
		$password		= $args[2];
		
		if(!$this->checkAccess($userName, $password)) 
		{
			return($this->error);
		}
		
		$categoryModel = $this->loadModel('categories');
		$categories = $categoryModel->listCategories();
		
		$categoriesStruct = array();
		
		foreach ($categories as $category) 
		{
			$struct['categoryId'] = $category['id'];
			$struct['categoryName'] = $category['category_name'];
			$struct['description'] = $category['category_describe'];
			$struct['parentId'] = 0;
			$struct['htmlUrl'] = $this->stack['static_var']['index'].'/category/'.$category['category_postname'].'/';
			$struct['rssUrl'] = $this->stack['static_var']['index'].'/rss/category/'.$category['category_postname'].'/';
			$categoriesStruct[] = $struct;
		}
		
		return $categoriesStruct;
	}
	
	public function mwNewMediaObject($args)
	{
		$blogId		= intval($args[0]);
		$userName		= $args[1];
		$password		= $args[2];
		$data        	= $args[3];
		
		$guid = mgGetGuid();
		$data['name'] = basename($data['name']);
		$path = __UPLOAD__.mgGetGuidPath($guid);
		if(!is_dir($path))
		{
			mgMkdir($path);
		}
		
		$success = file_put_contents($path.'/'.$guid ,$data['bits']);
		$fileModel = $this->loadModel('files');
		
		$insertId = 
		$fileModel->insertTable(array('file_name' => $data['name'],
							'file_type'	=> $data['type'],
							'file_guid'	=> $guid,
							'file_size'	=> strlen($data['bits']),
							'file_describe' => $data['name']));
									  
		if (!$success) 
		{
			return new IXR_Error(500, '写入'.$data['name'].'文件时出错.');
		}
		
		return array('file' => $data['name'],'url' => $this->stack['static_var']['index'].'/res/'.$insertId.'/'.$data['name'],'type' => $data['type']);
	}
	
	public function mtGetRecentPostTitles($args)
	{
		$blogId	= intval($args[0]);
		$userName	= $args[1];
		$password	= $args[2];
		$postsNum	= intval($args[3]);
		
		if(!$this->checkAccess($userName, $password)) 
		{
			return($this->error);
		}
		
		$postModel = $this->loadModel('posts');
		$posts = $postModel->listAllPosts($postsNum,0);
		

		if (!$posts_list) {
			$this->error = new IXR_Error(500, '没有任何文章.');
			return $this->error;
		}
		
		$struct = array();
		foreach($posts as $post)
		{
			$struct[] = array(
			'dateCreated' => new IXR_Date(date('Ymd\TH:i:s', $this->stack['static_var']['time_zone']+$post['post_time'])),
			'userid'    => $post['user_id'],
			'postid'  => $post['post_id'],
			'title'     => $post['post_title']
			);
		}
		
		return $struct;
	}
	
	public function mtGetCategoryList($args)
	{
		$blogId		= intval($args[0]);
		$userName		= $args[1];
		$password		= $args[2];
		
		if(!$this->checkAccess($userName, $password)) 
		{
			return($this->error);
		}
		
		$categoryModel = $this->loadModel('categories');
		$categories = $categoryModel->listCategories();
		
		$categoriesStruct = array();
		
		foreach ($categories as $category) 
		{
			$struct['categoryId'] = $category['id'];
			$struct['categoryName'] = $category['category_name'];

			$categoriesStruct[] = $struct;
		}
		
		return $categoriesStruct;
	}
	
	public function mtGetPostCategories($args)
	{
		$postId		= intval($args[0]);
		$userName		= $args[1];
		$password		= $args[2];
		
		if(!$this->checkAccess($userName, $password)) 
		{
			return($this->error);
		}
		
		$postModel = $this->loadModel('posts');
		$post = $postModel->fetchPostById($postId);
		
		return array(array('categoryName' => $post['category_name'], 'categoryId' => $post['category_id'], 'isPrimary' => true));
	}
	
	public function mtSetPostCategories($args)
	{
		$postId		= intval($args[0]);
		$userName		= $args[1];
		$password		= $args[2];
		$categories  	= $args[3];
		
		if(!$this->checkAccess($userName, $password)) 
		{
			return($this->error);
		}
		
		$postModel = $this->loadModel('posts');
		$post = $postModel->fetchPostById($postId);
		$category = array_pop($categories);
		$postModel->updateByKey($postId,array('category_id' => $category['categoryId']));
		
		return true;
	}
	
	public function pingbackPing($args)
	{
		$source = $args[0];
		$target = $args[1];
		
		if(strpos($target,$this->stack['static_var']['siteurl']) !== 0)
		{
			return new IXR_Error(0, __('这个链接地址错误.'));
		}
	}
	
	public function runModule()
	{
		if(!isset($GLOBALS['HTTP_RAW_POST_DATA']))
		{
			$GLOBALS['HTTP_RAW_POST_DATA'] = file_get_contents("php://input");
		}
		if(isset($GLOBALS['HTTP_RAW_POST_DATA']))
		{
			$GLOBALS['HTTP_RAW_POST_DATA'] = trim($GLOBALS['HTTP_RAW_POST_DATA']);
		}

		$this->stack['action']['auto_header'] = false;
		
		if(isset($_GET['rsd']))
		{
			echo '<?xml version="1.0" encoding="'.$this->stack['static_var']['charset'].'"?'.'>'; ?>
<rsd version="1.0" xmlns="http://archipelago.phrasewise.com/rsd">
  <service>
    <engineName>Magike</engineName>
    <engineLink>http://www.magike.net/</engineLink>
    <homePageLink><?php echo $this->stack['static_var']['siteurl'] ?></homePageLink>
    <apis>
      <api name="WordPress" blogID="1" preferred="false" apiLink="<?php echo $this->stack['static_var']['index'] ?>/xmlrpc.api" />
      <api name="Movable Type" blogID="1" preferred="true" apiLink="<?php echo $this->stack['static_var']['index'] ?>/xmlrpc.api" />
      <api name="MetaWeblog" blogID="1" preferred="false" apiLink="<?php echo $this->stack['static_var']['index'] ?>/xmlrpc.api" />
      <api name="Blogger" blogID="1" preferred="false" apiLink="<?php echo $this->stack['static_var']['index'] ?>/xmlrpc.api" />
    </apis>
  </service>
</rsd><?php
		}
		else
		{
			$this->$xmlrpcServer = new IxrServer($this->methods);
			return NULL;
		}
	}
 }
?>
