<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.get_skin_file.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class GetSkinFile extends MagikeModule
{
	public function runModule()
	{
		$template = isset($_GET['template']) ? $_GET['template'] : $this->stack['static_var']['template'];
		$now = isset($_GET['file']) ? $_GET['file'] : 'index.tpl';
		$file = __TEMPLATE__.'/'.$template.'/'.$now;
		$result = array();
		$result['content'] = file_exists($file) ? file_get_contents($file) : '';
		$result['content'] = isset($_GET['show']) ? $result['content'] : htmlspecialchars($result['content']);
		$result['writeable'] = is_writeable($file);
		$result['file'] = $now;
		return $result;
	}
}
?>
