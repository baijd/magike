<?php
/**********************************
 * Created on: 2006-11-30
 * File Name : module.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class Module
{
	function __construct()
	{

	}

	function stackPush($stackType,$stackName,$stackValue)
	{
		global $stack;
		$stack[$stackType][$stackName] = $stackValue;
	}

	function stackPop($stackType,$stackName)
	{
		global $stack;
		return $stack[$stackType][$stackName];
	}
}
?>
