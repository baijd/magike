<?php
/**********************************
 * Created on: 2007-3-6
 * File Name : module.tags.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class Tags extends MagikeModule
{
	public function runModule($args)
	{
		$require = array('limit' => 0,'order' => 0,'title' => 1);
		$getArgs = $this->initArgs($args,$require);
		$tagModel = $this->loadModel("tags");
		
		if($getArgs['title'])
		{
			$this->stack['static_var']['blog_title'] = '标签 &raquo; '.$this->stack['static_var']['blog_name'];
		}
		return $tagModel->tagClouds($getArgs['limit'],$getArgs['order']);
	}
}
?>
