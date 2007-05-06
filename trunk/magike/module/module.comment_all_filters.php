<?php
/**********************************
 * Created on: 2007-3-3
 * File Name : module.comment_all_filters.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class CommentAllFilters extends MagikeModule
{
	public function praseFilter(&$str)
	{
		$filter = str_replace('comment_filter.','',$str);
		$filterLang = $this->getLanguage($filter,'comment_filter');
		$str = array('filter_name' => $filter,'filter_lang' => $filterLang);
	}
	
	public function runModule()
	{
		$files = mgGetFile(__MODULE__.'/comment_filter',false,'php');
		array_walk($files,array($this,'praseFilter'));
		return $files;
	}
}
?>
