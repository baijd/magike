<?php
/**********************************
 * Created on: 2007-3-4
 * File Name : module.links_prase_list.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class LinksPraseList extends MagikeModule
{
	private $linkModel;
	private $getArgs;
	
	public function praseLinkCategory($val)
	{
		
	}
	
	public function runModule($args)
	{
		$require = array('limit'  => 10);
		$this->getArgs = $this->initArgs($args,$require);
		
		$this->linkModel = $this->loadModel('links');
		$linkCategoryModel = $this->loadModel('link_categories');
		return $linkCategoryModel->listUnhiddenLinkCategories(array('function' => array($this,'praseLinkCategory')));
	}
}
?>
