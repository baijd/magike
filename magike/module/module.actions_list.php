<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.actions_list.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class ActionsList extends MagikeModule
{
	public function runModule()
	{
		$actionList = array();
		$dirs = mgGetDir(__DIR__.'/action');
		foreach($dirs as $key => $val)
		{
			$item = array();
			$item['word'] = $this->getLanguage($val,'path_action');
			$item['value'] = $val;
			$actionList[] = $item;
		}
		return $actionList;
	}
}
?>
