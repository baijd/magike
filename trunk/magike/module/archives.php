<?php
/**********************************
 * Created on: 2006-12-6
 * File Name : archives.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class ArchivesModule extends MagikeModule
{
	function runModule()
	{
		$this->template->data['archives'] = array();
		$this->database->fectch(array('table' => 'table.posts',
									  'where' => array(
									  	'template' => "publish = 'publish' AND type = 'archive'"
									  ),
									  'orderby' => 'time DESC')
		,array('function' => array($this,'pushArchivesValue')));
	}

	public function pushArchivesValue($val)
	{
		$val['tags'] = explode(',',$val['tags']);
		$this->template->data['archives'][] = $val;
	}
}
?>
