<?php
/**********************************
 * Created on: 2007-3-2
 * File Name : module.is_recent.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class IsRecent extends Comments
{
	public function runModule($args)
	{
		$require = array('limit'  => $this->stack['static_var']['comment_list_num'],		//每页显示多少,0表示都显示
					  'substr' => 0,			//摘要字数,0表示不摘要
					  'sub' => $this->stack['static_var']['post_sub'],
					  'time_format' => $this->stack['static_var']['comment_date_format'],	//日期输出格式
					  'orderby' => 'table.comments.comment_date',		//排序索引
					  'striptags' => 1,
					  'content' => 0,
					  'sort'   => 'DESC');		//输出哪些状态,NULL表示都显示
		
		$this->getArgs = $this->initArgs($args,$require);
		
		return $this->model->getAllPublishCommentsTrackbacksPingbacks($this->getArgs['limit'],
										0,
										$this->getArgs['sort'],
										$this->getArgs['orderby'],
										array('function' => array($this,'praseComment')));
	}
}
?>
