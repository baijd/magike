<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.http_location.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class HttpLocation extends MagikeModule
{
	public function runModule($args)
	{
		$require = array('referer' => 'get');
		$getArgs = $this->initArgs($args,$require);
		if('get' == $getArgs['referer'])
		{
			header('location: '.$_GET['referer']);
		}
		else if('post' == $getArgs['referer'])
		{
			header('location: '.$_POST['referer']);
		}
	}
}
?>
