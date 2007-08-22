<?php
/**********************************
 * Created on: 2007-3-3
 * File Name : module.comment_filter.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class CommentFilter extends MagikeModule
{
	private $result;
	private $filterType;
	
	function __construct()
	{
		parent::__construct();
		$cache = new Cache();
		$cache->checkCacheFile(array($this->cacheFile => array('listener' => 'fileExists',
													'callback' => array($this,'buildCache')
								)));
	}
	
	public function buildCache()
	{
		$filterModel = $this->loadModel('comment_filters');
		$filter = $filterModel->fetch(array('table' => 'table.comment_filters'));
		$filterByType = array();
		foreach($filter as $val)
		{
			if(!isset($filterByType[$val['comment_filter_type']]))
			{
				$filterByType[$val['comment_filter_type']] = array();
			}
			$filterByType[$val['comment_filter_type']][$val['comment_filter_name']] = $val['comment_filter_value'];
			if('all' == $val['comment_filter_type'])
			{
				$filterByType['comment'][$val['comment_filter_name']] = $val['comment_filter_value'];
				$filterByType['ping'][$val['comment_filter_name']] = $val['comment_filter_value'];
			}
		}
		
		mgExportArrayToFile($this->cacheFile,$filterByType,'filter');
	}
	
	public function filterComment()
	{
		$filter = array();
		require($this->cacheFile);
		$filterByType = isset($filter[$this->filterType]) ? $filter[$this->filterType] : array();
		$requireDir = __MODULE__.'/'.$this->moduleName.'/';

		foreach($filterByType as $key => $val)
		{
			$currentFile = $requireDir.'comment_filter.'.$key.'.php';
			if(file_exists($currentFile))
			{
				$tmp = null;
				require_once($currentFile);
				$object = mgFileNameToClassName($key);
				eval('$tmp = new '.$object.'($val);');
				
				//result返回一个数组,包含两个键值publish和word
				$this->result = call_user_func(array($tmp,'runFilter'));
				if($this->result)
				{
					break;
				}
			}
		}
	}
	
	public function runModule($args)
	{
		$this->result = array();
		$require = array('key' => NULL,'val' => NULL,'type' => 'all');
		$getArgs = $this->initArgs($args,$require);
		$this->filterType = $getArgs['type'];
		
		if(NULL == $getArgs['key'] && NULL == $getArgs['val'])
		{
			$this->filterComment();
		}
		else if(NULL != $getArgs['key'] && NULL == $getArgs['val'])
		{
			$this->onPost($getArgs['key'],'filterComment');
		}
		else if(NULL != $getArgs['key'] && NULL != $getArgs['val'])
		{
			$this->onPost($getArgs['key'],'filterComment',$getArgs['val']);
		}
		else
		{
			return $this->result;
		}
		
		return $this->result;
	}
}
?>
