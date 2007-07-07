<?php
/**********************************
 * Created on: 2007-3-3
 * File Name : module.comment_filters_list.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class CommentFiltersList extends MagikeModule
{
	function __construct()
	{
		parent::__construct(array('public' => array('database')));
	}
	
	public function praseFilter($val)
	{
		if($filterName = $this->getLanguage($val['comment_filter_name'],'comment_filter'))
		{
			$val['comment_filter_name'] = $filterName;
		}
		
		$val['comment_filter_type'] = $this->getLanguage('filter_'.$val['comment_filter_type'],'comment_filter');
		$val['comment_filter_value'] = mgStripTags(mgSubStr($val['comment_filter_value'],0,30));
		return $val;
	}
	
	public function runModule()
	{
		return $this->database->fetch(array('table' => 'table.comment_filters'),array('function' => array($this,'praseFilter')));
	}
}
?>
