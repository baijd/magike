<?php
/**********************************
 * Created on: 2006-12-17
 * File Name : module.recent_posts.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class RecentPosts extends MagikeModule
{
	public function prasePost($val)
	{
		$val["post_time"] = 
		date($this->stack['static_var']['post_date_format'],$this->stack['static_var']['time_zone']+$val["post_time"]);
		return $val;
	}
	
	public function runModule()
	{
		$postModel = $this->loadModel('posts');
		return $postModel->listAllEntries($this->stack['static_var']['post_list_num'],0,array('function' => array($this,'prasePost')));
	}
}
?>
