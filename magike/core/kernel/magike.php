<?php
/**********************************
 * Created on: 2006-11-30
 * File Name : magike.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class Magike extends MagikeObject
{
	function __construct()
	{
		parent::__construct(array('public' => array('stack',
													'static',
													'path',
													'access',
													'action')));
		print_r($this->stack->data);
	}
}
?>
