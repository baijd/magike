<?php
/**********************************
 * Created on: 2006-12-3
 * File Name : model.paths.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class PathsModel extends MagikeModel
{
	public function listPaths()
	{
		return $this->fectch(array('table' => 'table.paths','orderby' => 'id','sort' => 'ASC'));
	}
}
?>
