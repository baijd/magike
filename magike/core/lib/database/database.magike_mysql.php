<?php
/**********************************
 * Created on: 2007-2-2
 * File Name : database.magike_mysql.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
define('E_DATABASE','Database Error');
class MagikeMysql extends MagikeObject
{
	private $resource;

	function __construct()
	{
		$dblink=@mysql_connect(__DBHOST__,__DBUSER__,__DBPASS__) or die('Database Connect Error');
		@mysql_select_db(__DBNAME__,$dblink) or die('Database Connect Error');
		$this->query('SET NAMES "utf8"');
	}
	
	public function getInsertId($resource = NULL)
	{
		$resource = $resource ? $resource : $this->resource;
		return mysql_insert_id($resource);
	}
	
	public function getAffectedRows($resource = NULL)
	{
		$resource = $resource ? $resource : $this->resource;
		return mysql_affected_rows($resource);
	}
	
	public function getRowsNumber($resource = NULL)
	{
		$resource = $resource ? $resource : $this->resource;
		return mysql_num_rows($resource);
	}
	
	public function databaseException()
	{
		$this->throwException(E_DATABASE,__DEBUG__ ? $query."<br />\n".mysql_error() : NULL);
	}
	
	public function query($query,$op = 'r')
	{
		mgTrace();
		$this->resource =  mysql_query($query) or $this->databaseException($query);
		mgTrace(false);
		mgDebug('Excute SQL',$query);
		return $this->resource;
	}
	
	public function fectchArray($resource = NULL)
	{
		return mysql_fetch_array($resource ? $resource : $this->resource,MYSQL_ASSOC);
	}
}
?>
