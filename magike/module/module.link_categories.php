<?php
/**********************************
 * Created on: 2007-3-4
 * File Name : module.link_categories.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class LinkCategories extends MagikeModule
{
	public function runModule()
	{
		$linkCategoriesModel = $this->loadModel("link_categories");
		return $linkCategoriesModel->listLinkCategories();
	}
}
?>
