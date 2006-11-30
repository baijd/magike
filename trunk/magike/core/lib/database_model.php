<?php
/**********************************
 * Created on: 2006-11-30
 * File Name : database_model.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

 $dblink=@mysql_connect(__DBHOST__,__DBUSER__,__DBPASS__) or die(Module::_exception(E_DATABASEINSTALL,mysql_error()));
 @mysql_select_db(__DBNAME__,$dblink) or die(Module::_exception(E_DATABASECONNECT,mysql_error(),"error_database_callback"));

 class DatabaseModel extends Module
 {
 	function __construct()
 	{

 	}

 	function fectch()
 	{
 		$times = $this->magikeStackPop('system','query_times');
 		$this->maikeStackPush('system','query_times',$times);
 	}
 }
?>
