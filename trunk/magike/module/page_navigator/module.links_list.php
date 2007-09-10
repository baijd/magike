<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.links_list.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class LinksList extends PageNavigator
{
	public function runModule($args)
	{
		$require = array('limit'  => 20);
		$getArgs = $this->initArgs($args,$require);
		
		$linkModel = $this->loadModel('links');
		$total = $linkModel->countTable();
		
		return $this->makeClassicNavigator($getArgs['limit'],$total,'admin/links/link_list/?link_page=','link_page');
	}
}
?>
