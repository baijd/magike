<?php
/**********************************
 * Created on: 2006-12-3
 * File Name : model.groups.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class GroupsModel extends MagikeModel
{
	function __construct()
	{
		parent::__construct('table.groups','id');
	}
	
	public function listGroups()
	{
		return $this->fetch(array('table' => 'table.groups','orderby' => 'id','sort' => 'ASC'));
	}
	
	public function getUserGroups($id)
	{
		return $this->fetch(array('table' => 'table.groups JOIN table.user_group_mapping ON table.groups.id = table.user_group_mapping.group_id',
								   'where' => array('template' => 'table.user_group_mapping.user_id = ?','value' => array($id)),
								   'groupby' => 'table.groups.id'
									)
							);
	}
	
	public function getGroupPaths($id)
	{
		return $this->fetch(array('table' => 'table.groups JOIN table.path_group_mapping ON table.groups.id = table.path_group_mapping.group_id',
								   'where' => array('template' => 'table.groups.id = ?','value' => array($id)),
								   'groupby' => 'table.path_group_mapping.path_id'
									)
							);
	}
	
	public function getPathGroups($id)
	{
		return $this->fetch(array('table' => 'table.groups JOIN table.path_group_mapping ON table.groups.id = table.path_group_mapping.group_id',
								   'where' => array('template' => 'table.path_group_mapping.path_id = ?','value' => array($id)),
								   'groupby' => 'table.path_group_mapping.group_id'
									)
							);
	}
	
	public function insertUserGroup($uid,$gid)
	{
		$this->insert(array('table' => 'table.user_group_mapping',
									  'value' => array('user_id' => $uid,'group_id' => $gid)
								));
	}
	
	public function deleteUserGroup($id)
	{
		$this->delete(array('table' => 'table.user_group_mapping',
									  'where' => array('template' => 'user_id = ?',
													   'value'	  => array($id)
								)
								));
	}
	
	public function deleteGroupUser($id)
	{
		$this->delete(array('table' => 'table.user_group_mapping',
									  'where' => array('template' => 'group_id = ?',
													   'value'	  => array($id)
								)
								));
	}
	
	public function insertPathGroup($pid,$gid)
	{
		$this->insert(array('table' => 'table.path_group_mapping',
									  'value' => array('path_id' => $pid,'group_id' => $gid)
								));
	}
	
	public function deletePathGroup($id)
	{
		$this->delete(array('table' => 'table.path_group_mapping',
									   'where' => array('template' => 'group_id = ?',
													   'value'	  => array($id)
								)
								));
	}
	
	public function deleteGroupPath($id)
	{
		$this->delete(array('table' => 'table.path_group_mapping',
									   'where' => array('template' => 'path_id = ?',
													   'value'	  => array($id)
								)
								));
	}
}
?>
