<?php
/**********************************
 * Created on: 2007-3-4
 * File Name : module.paths_list.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class PathsList extends MagikeModule
{
	public function prasePath($val)
	{
		$val['path_action'] = $this->getLanguage($val['path_action'],'path_action');
		return $val;
	}
	
	public function runModule()
	{
		$pathModel = $this->loadModel('paths');
		return $pathModel->listPaths(array('function' => array($this,'prasePath')));
	}
}
?>
 