<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.http_header.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class HttpHeader extends MagikeModule
{
	public function runModule($args)
	{
		$require = array('content_type' => $this->stack['static_var']['content_type'],
						 'charset' => $this->stack['static_var']['charset']);
		$getArgs = $this->initArgs($args,$require);
		$this->stack['static_var']['content_type'] = $getArgs['content_type'];
		$this->stack['static_var']['charset'] = $getArgs['charset'];
		return NULL;
	}
}
?>
