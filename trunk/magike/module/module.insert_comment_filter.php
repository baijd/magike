<?php
/**********************************
 * Created on: 2007-3-3
 * File Name : module.insert_comment_filter.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class InsertCommentFilter extends MagikeModule
{
	public function runModule()
	{
		$result = array();
		
		if(isset($_GET['cf_id']))
		{
			$filterModel = $this->loadModel('comment_filters');
			$result = $filterModel->fectchOneByKey($_GET['cf_id']);
			$this->stack['admin_menu_list']['children'][2]['menu_name'] = '编辑 "'.mgSubStr($this->getLanguage($result['comment_filter_name'],'comment_filter'),0,15,'...').'"';
			$this->stack['admin_menu_list']['children'][2]['path_name'] = '/admin/comments/filter/?cf_id='.$_GET['cf_id'];
			$this->stack['static_var']['admin_title'] = '编辑 "'.mgSubStr($this->getLanguage($result['comment_filter_name'],'comment_filter'),0,15,'...').'"';
			$result['do'] = 'update';
			return $result;
		}
		else
		{
			$result['comment_filter_type'] = 'all';
			$result['do'] = 'insert';
			$result['comment_filter_name'] = NULL;
			return $result;
		}
	}
}
