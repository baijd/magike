<?php
/**********************************
 * Created on: 2006-12-19
 * File Name : admin_menu_module.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class AdminMenuList extends MagikeModule
{
	private $result;
	
	function __construct()
	{
		parent::__construct(array('public' => array('database')));
		$this->result = array();
	}
	
	private function praseFocus($array,$hasChild = true)
	{
		if(NULL != $array)
		{
			$focus = 0;
			$lastDifferent = 0;

			foreach($array as $key => $val)
			{
				$diff = count(array_intersect_assoc(explode('/',$val['path_name']),explode('/',$this->stack['action']['path'])));
				if($diff > $lastDifferent)
				{
					$focus = $key;
					$lastDifferent = $diff;
				}
				$array[$key]['focus'] = false;
			}

			if($hasChild)
			{
				$this->praseFocusChild($array[$focus]['menu_id']);
			}
			$array[$focus]['focus'] = true;
		}
		return $array;
	}

	private function praseFocusChild($id)
	{
		$this->result['children'] = $this->database->fectch(array('table' => 'table.menus JOIN table.paths ON table.menus.path_id = table.paths.id',
																  'groupby' => 'table.menus.id',
																  'where' => array('template' => 'menu_parent = ?','value' => array($id))
																  ),
																  array('function' => array($this,'pushMenu')));
		$this->result['children'] = $this->praseFocus($this->result['children'],false);
	}

	public function runModule()
	{
		$this->result['parents'] = $this->database->fectch(array('fields'=> '*,table.menus.id as menu_id',
																 'table' => 'table.menus JOIN table.paths ON table.menus.path_id = table.paths.id',
																 'groupby' => 'table.menus.id',
																 'where' => array('template' => 'menu_parent = 0'
																 )),
																 array('function' => array($this,'pushMenu')));
		$this->result['parents'] = $this->praseFocus($this->result['parents']);
		return $this->result;
	}

	public function pushMenu($val)
	{
		$lang = explode('.',$val['menu_name']);
		$val['menu_name'] = ('lang' == $lang[0]) ? $this->getLanguage($lang[1],$lang[2]) : $val['menu_name'];
		return $val;
	}
}
?>
