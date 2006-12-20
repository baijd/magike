<?php
/**********************************
 * Created on: 2006-12-19
 * File Name : admin_menu_module.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class AdminMenuModule extends MagikeModule
{
	public function runModule()
	{
		$this->template->data['admin_menu']['parent'] = $this->database->fectch(array('table' => 'table.menus',
																		  			  'where' => array('template' => 'menu_parent = 0'
																		  				)),
																		  		array('function' => array($this,'pushMenu')));
		$this->template->data['admin_menu']['parent'] = $this->praseFocus($this->template->data['admin_menu']['parent']);
	}

	public function pushMenu($val)
	{
		$lang = explode('.',$val['menu_name']);
		$val['menu_name'] = ('lang' == $lang[0]) ? $this->getLanguage($lang[1],$lang[2]) : $val['menu_name'];
		return $val;
	}

	private function praseFocus($array)
	{
		$focus = 0;
		$lastDifferent = 0;

		foreach($array as $key => $val)
		{
			$diff = count(array_intersect_assoc(explode('/',$val['menu_path']),explode('/',$this->stack->data['system']['path'])));
			if($diff > $lastDifferent)
			{
				$focus = $key;
				$lastDifferent = $diff;
			}
			$array[$key]['focus'] = false;
		}

		$array[$focus]['focus'] = true;
		return $array;
	}
}
?>
