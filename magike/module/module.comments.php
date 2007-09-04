<?php
/**********************************
 * Created on: 2007-3-2
 * File Name : module.comments.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class Comments extends Posts
{
	function __construct()
	{
		parent::__construct(false);
		$this->model = $this->loadModel('comments');
	}
	
	public function praseComment($val,$num,$last)
	{
		$val = $this->prasePost($val,$num,$last);
		
		if($val['comment_publish'] == 'waitting' || $val['comment_publish'] == 'spam')
		{
			if($_SERVER["REMOTE_ADDR"] != $val['comment_ip'] || $_SERVER["HTTP_USER_AGENT"] != $val['comment_agent'])
			{
				return false;
			}
		}
		
		$val['comment_text'] = mgStripTags($val['comment_text']);
		$val['comment_text'] = $this->getArgs['striptags'] ? $val["comment_text"] : nl2br($val["comment_text"]);
		$val['comment_text'] = $this->getArgs['substr'] ? mgSubStr($val['comment_text'],0,$this->getArgs['substr']) : $val['comment_text'];
		$val['comment_date'] = date($this->getArgs['time_format'],$this->stack['static_var']['time_zone'] + $val['comment_date']);
		$val['comment_alt']	 = $num%2;
		$val['comment_is_last'] = $last;
		return $val;
	}
	
	public function runModule($args)
	{
		$require = array('limit'  => null,		//每页显示多少,0表示都显示
					  'substr' => 0,			//摘要字数,0表示不摘要
					  'sub' => $this->stack['static_var']['post_sub'],
					  'time_format' => $this->stack['static_var']['comment_date_format'],	//日期输出格式
					  'orderby' => 'table.comments.id',		//排序索引
					  'striptags' => 1,
					  'content' => 0,
					  'sort'   => 'DESC');		//输出哪些状态,NULL表示都显示
		
		$this->getArgs = $this->initArgs($args,$require);
		$this->getPage($this->getArgs['limit'],'comment_page');
		
		return $this->model->getAllComments($this->getArgs['limit'],
										$this->offset,
										$this->getArgs['sort'],
										$this->getArgs['orderby'],
										array('function' => array($this,'praseComment')));
	}
}
?>
