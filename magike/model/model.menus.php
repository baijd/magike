<?php
/**********************************
 * Created on: 2006-12-3
 * File Name : model.menus.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class MenusModel extends MagikeModel
{
	function __construct()
	{
		parent::__construct('table.menus','id');
	}
}
?>
