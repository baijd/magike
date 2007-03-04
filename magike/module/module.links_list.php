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
		return $this->database->fectch(array('table' => 'table.links',
											 'offset' => isset($_GET['page']) ? $_GET['page'] : 0,
											 'limit' => 20,
											 'orderby' => 'id',
											 'sort' => 'DESC'));
	}
}
?>
