<?php
/**********************************
 * Created on: 2007-3-2
 * File Name : module.comments_list.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class CommentsList extends MagikeModule
{
	function __construct()
	{
		parent::__construct();
		$this->model = $this->loadModel('comments');
	}
	
	public function praseComment($val,$num,$last,$data)
	{
		if($val['comment_publish'] == 'waitting' || $val['comment_publish'] == 'spam')
		{
			if($_SERVER["REMOTE_ADDR"] != $val['comment_ip'] || $_SERVER["HTTP_USER_AGENT"] != $val['comment_agent'])
			{
				return false;
			}
		}
		$val['comment_text'] = nl2br(mgStripTags($data['substr'] ? mgSubStr($val['comment_text'],0,$data['substr'],$data['trim']) : $val['comment_text']));
		$val['comment_date'] = date($data['datefmt'],$this->stack['static_var']['time_zone'] + $val['comment_date']);
		$val['comment_alt']	 = $num%2;
		return $val;
	}
	
	public function runModule($args)
	{
		$require = array('limit'  => 0,				//每页显示多少,0表示都显示
						 'list'	  => 0,				//是否为列表形式,如果不是则还要从GET值中获取一个post_id或者post_name值作为条件索引
						 'substr' => 0,				//摘要字数,0表示不摘要
						 'trim'	  => '...',			//摘要显示
						 'datefmt'=> $this->stack['static_var']['comment_date_format'],	//日期输出格式
						 'orderby'=> 'id',//排序索引
						 'sort'   => 'DESC',		//排序顺序
						 'type'   => NULL);			//输出哪些状态,NULL表示都显示
		$getArgs = $this->initArgs($args,$require);
		
		if(0 != $getArgs['limit'])
		{
			$query['offset'] = isset($_GET['comment_page']) ? $_GET['comment_page'] : 0;
			$query['limit']  = $getArgs['limit'];
		}
		
		$query['orderby'] = 'table.comments.'.$getArgs['orderby'];
		$query['sort'] = $getArgs['sort'];
		$query['where']['template'] = '1 = 1';
		$query['where']['value'] = array();
		
		if($getArgs['list'])
		{
			$query['table'] = 'table.comments';
		}
		else
		{
			$query['fields'] = '*,table.comments.id AS comment_id';
			$query['table'] = 'table.posts JOIN table.comments ON table.comments.post_id = table.posts.id';
			$query['groupby'] = 'table.comments.id';
			if(isset($_GET['post_id']))
			{
				$query['where']['template'] .= ' AND table.posts.id = ?';
				$query['where']['value'][] = $_GET['post_id'];
			}
			if(isset($_GET['post_name']))
			{
				$query['where']['template'] .= ' AND table.posts.post_name = ?';
				$query['where']['value'][] = $_GET['post_name'];
			}
		}
		
		if($getArgs['type'])
		{
			$query['where']['template'] .= ' AND table.comments.comment_type = ?';
			$query['where']['value'][] = $getArgs['type'];
		}
	
		return $this->model->fectch($query,array('function' => array($this,'praseComment'),'data' => $getArgs));
	}
}
?>
