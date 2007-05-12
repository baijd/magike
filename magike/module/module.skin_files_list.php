<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.skin_files_list.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class SkinFilesList extends MagikeModule
{
	public function runModule()
	{
		$template = isset($_GET['template']) ? $_GET['template'] : $this->stack['static_var']['template'];
		$dir = __TEMPLATE__.'/'.$template;
		$result = array();
		$files = mgGetFile($dir,true,'tpl|css|js');
		foreach($files as $val)
		{
			$item = array();
			$item['name'] = $this->getLanguage($val,'skin_file');
			$item['file'] = $val;
			$item['icon'] = file_exists(__TEMPLATE__.'/'.$this->stack['static_var']['admin_template'].'/images/elements/'.$item['file'].'.gif');
			$result[] = $item;
		}
		
		return $result;
	}
}
?>
