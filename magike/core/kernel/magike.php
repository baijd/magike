<?php
/**********************************
 * Created on: 2006-11-30
 * File Name : magike.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class Magike extends MagikeObject
{
	function __construct($args)
	{
		parent::__construct(array('public' => array('stack',
													'static')));
	}
}
?>
