<?php
/**********************************
 * Created on: 2007-3-4
 * File Name : module.links_list.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class LinksList extends MagikeModule
{
	function __construct()
	{
		parent::__construct(array('public' => array('database')));
	}
	
	public function runModule()
	{
		$linkModel = $this->loadModel('links');
		$page = isset($_GET['link_page']) ? $_GET['link_page'] : 1;
		$offset = ($page - 1)*20;
		return $linkModel->listLinks(20,$offset);
	}
}
?>
