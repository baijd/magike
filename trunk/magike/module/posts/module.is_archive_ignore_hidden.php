<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.is_archive_ignore_hidden.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
 class IsArchiveIgnoreHidden extends Posts
 {	
	public function runModule($args)
	{
		$require = array(  'sub' 	  	=> $this->stack['static_var']['post_sub'],	//摘要字数
					 'limit'  		=> $this->stack['static_var']['post_page_num'],//每页篇数
					 'striptags'		=> 0,
					 'content'		=> 0,
					 'time_format'	=> $this->stack['static_var']['post_date_format'],
					);
		$this->getArgs = $this->initArgs($args,$require);
		$this->getPage($this->getArgs['limit']);
		
		return $this->model->listAllEntriesIncludeHidden($this->getArgs['limit'],$this->offset,array('function' => array($this,'prasePost')));
	}
 }
 ?>
