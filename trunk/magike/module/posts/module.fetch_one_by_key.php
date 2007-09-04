<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.fetch_one_by_key.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
 class FetchOneByKey extends Posts
 {
	public function getAccess()
	{
		setcookie('post_password',$_POST['post_password'],0,'/');
		$_COOKIE['post_password'] = $_POST['post_password'];
		@reset($_COOKIE);
	}
	
	public function runModule($args)
	{
		$result = array();
		$require = array('sub' 	  	=> $this->stack['static_var']['post_sub'],	//摘要字数
					 'striptags'		=> 0,
					 'content'		=> 1,
					 'time_format'	=> $this->stack['static_var']['post_date_format'],
					);
		$this->getArgs = $this->initArgs($args,$require);
		
		$this->onPost('post_password','getAccess');
		if(isset($_GET['post_id']))
		{
			$result = $this->model->fetchPostById($_GET['post_id'],array('function' => array($this,'prasePost')));
		}
		else if(isset($_GET['post_name']))
		{
			$result = $this->model->fetchPostByName($_GET['post_name'],array('function' => array($this,'prasePost')));
		}
		else
		{
			return array();
		}
		
		if(isset($this->stack['static_var']['blog_name']))
		{
			$this->stack['static_var']['blog_title'] = $result['post_title'].' &raquo; '.$this->stack['static_var']['blog_name'];
		}
		if(isset($this->stack['static_var']['keywords']) && $result['post_tags'])
		{
			$this->stack['static_var']['keywords'] = $this->stack['static_var']['keywords'].','.implode(",",$result['post_tags']);
		}
		$this->stack['static_var']['rss_permalink'] = $result["rss_permalink"];
		
		//处理隐藏文章
		if($result['post_is_hidden'])
		{
			if((isset($_COOKIE['post_password']) && $_COOKIE['post_password'] == $result['post_password'])
			|| $this->stack['access']['user_id'] == $result['user_id'])
			{
				$result['post_access'] = true;
			}
			else
			{
				$result['post_access'] = false;
			}
		}
		else
		{
			$result['post_access'] = true;
		}
		
		return $result;
	}
 }
 ?>
