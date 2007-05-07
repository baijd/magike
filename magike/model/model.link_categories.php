<?php
/**********************************
 * Created on: 2006-12-3
 * File Name : model.link_categories.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class LinkCategoriesModel extends MagikeModel
{
	public function listLinkCategories()
	{
		return $this->fectch(array('table' => $this->table,
								   'orderby' => 'table.link_categories.link_category_sort',
								   'sort' => 'ASC'));
	}
}
?>
