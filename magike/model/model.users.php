<?php
/**********************************
 * Created on: 2006-12-3
 * File Name : model.users.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class UsersModel extends MagikeModel
{
	public function listUsers()
	{
		return $this->fectch(array('table' => 'table.users','orderby' => 'id','sort' => 'ASC'));
	}
}
?>
