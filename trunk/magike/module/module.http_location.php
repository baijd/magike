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
		$require = array('referer' => NULL);
		$getArgs = $this->initArgs($args,$require);
		
		if(isset($_POST) && $_POST)
		{
			if('get' == $getArgs['referer'])
			{
				header('location: '.$_GET['referer']);
			}
			else if('post' == $getArgs['referer'])
			{
				header('location: '.$_POST['referer']);
			}
			else
			{
				header('location: '.$this->stack['static_var']['siteurl']);
			}
		}
	}
}
?>
