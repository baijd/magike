<?php
/**********************************
 * Created on: 2007-3-2
 * File Name : module.comments_list_all.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class CommentsListAll extends MagikeModule
{
	function __construct()
	{
		parent::__construct(array('public' => array('database')));
	}
	
	public function praseComment($val,$num,$last,$data)
	{
		$val['comment_text'] = $data['striptags'] ? mgStripTags($val["comment_text"]) : $val["comment_text"];
		$val['comment_text'] = mgStripTags($data['substr'] ? mgSubStr($val['comment_text'],0,$data['substr'],$data['trim']) : $val['comment_text']);
		$val['comment_date'] = date($data['datefmt'],$this->stack['static_var']['time_zone'] + $val['comment_date']);
		$val['comment_alt']	 = $num%2;
		return $val;
	}
	
	public function runModule($args)
	{
		$require = array('limit'  => $this->stack['static_var']['comment_list_num'],				//每页显示多少,0表示都显示
						 'substr' => 0,				//摘要字数,0表示不摘要
						 'trim'	  => '...',			//摘要显示
						 'striptags' => 0,			//去除标签
						 'datefmt'=> $this->stack['static_var']['comment_date_format'],	//日期输出格式
						 'orderby'=> 'comment_date',//排序索引
						 'sort'   => 'DESC');
		
		$getArgs = $this->initArgs($args,$require);
		$page = isset($_GET['comment_page']) ? $_GET['comment_page'] : 1;
		$offset = ($page - 1)*$getArgs['limit'];
		
		$commentsModel = $this->loadModel('comments');
		
		return $commentsModel->getAllComments($getArgs['limit'],$offset,$getArgs['sort'],$getArgs['orderby'],array('function' => array($this,'praseComment'),'data' => $getArgs));
	}
}
?>
