<?php
/**********************************
 * Created on: 2006-12-3
 * File Name : model.paths.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class PathsModel extends MagikeModel
{
	public function listPaths($func = NULL)
	{
		return $this->fetch(array('table' => 'table.paths','orderby' => 'id','sort' => 'ASC'),$func);
	}
	
	public function checkPathAccess($groups,$path = '/')
	{
		$result = 	  $this->fetch(array('table' => 'table.paths JOIN table.path_group_mapping ON table.paths.id = table.path_group_mapping.path_id',
							      'groupby' => 'table.path_group_mapping.id',
							      'where'    => array('template' => "table.paths.path_name = '{$path}'")));
		
		$userGroups = array();
		foreach($result as $val)
		{
			$userGroups[] = $val['group_id'];
		}
		
		return array_intersect($userGroups,$groups);
	}
}
?>
