<?php
/**********************************
 * Created on: 2006-12-7
 * File Name : static.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class StaticModule extends MagikeModule
{
	public function runModule()
	{
		$this->stack->setStack('static','title',$this->stack->data['static']['blog_name']);
		$this->template->data['static'] = $this->stack->data['static'];
	}
}
?>
