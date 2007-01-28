<?php
/**********************************
 * Created on: 2006-12-7
 * File Name : system.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class SystemModule extends MagikeModule
{
	public function runModule()
	{
		$this->template->data['system'] = $this->stack->data['system'];
	}
}
?>