<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.links_page_nav.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class LinksPageNav extends MagikeModule
{
	public function runModule()
	{
		$linkModel = $this->loadModel('links');
		
		$total = $linkModel->countTable();
		$page = isset($_GET['link_page']) ? $_GET['link_page'] : 1;
		$result['next'] = $total > $page*20 ? $page + 1 : 0;
		$result['prev'] = $page > 1 ? $page - 1 : 0;
		$result['total'] = $total%20 > 0 ? intval($total/20) + 1 : intval($total/20);
		
		return $result;
	}
}
?>
