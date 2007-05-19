<?php
/**********************************
 * Created on: 2006-12-3
 * File Name : model.files.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class FilesModel extends MagikeModel
{
	public function listFiles($limit = 5,$offset = 0,$func = NULL)
	{
		return $this->fectch(array('table' => 'table.files',
								   'orderby' => 'id',
								   'limit'	=> $limit,
								   'offset' => $offset,
								   'sort' => 'DESC'),$func);
	}
}
?>
