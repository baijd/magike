<?php
/**********************************
 * Created on: 2007-3-2
 * File Name : module.fetch_by_post.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class FetchByPost extends Comments
{
	public function runModule($args)
	{
		$require = array('limit'  => NULL,		//每页显示多少,0表示都显示
					  'substr' => 0,			//摘要字数,0表示不摘要
					  'sub' => $this->stack['static_var']['post_sub'],
					  'time_format' => $this->stack['static_var']['comment_date_format'],	//日期输出格式
					  'orderby' => 'table.comments.comment_date',		//排序索引
					  'striptags' => 0,
					  'content' => 0,
					  'sort'   => 'DESC');		//输出哪些状态,NULL表示都显示
		
		$this->getArgs = $this->initArgs($args,$require);
		$this->getPage($this->getArgs['limit'],'comment_page');
		
		if(isset($_GET['post_id']))
		{
			return $this->model->getAllPublishCommentsTrackbacksPingbacks($this->getArgs['limit'],
											$this->offset,
											$this->getArgs['sort'],
											$this->getArgs['orderby'],
											array('function' => array($this,'praseComment')),
											'post_id',
											$_GET['post_id']);
		}
		
		else if(isset($_GET['post_name']))
		{
			return $this->model->getAllPublishCommentsTrackbacksPingbacks($this->getArgs['limit'],
											$this->offset,
											$this->getArgs['sort'],
											$this->getArgs['orderby'],
											array('function' => array($this,'praseComment')),
											'post_name',
											$_GET['post_name']);
		}
		
		else
		{
			return array();
		}
	}
}
?>
