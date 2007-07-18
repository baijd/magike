<?php
/**********************************
 * Created on: 2007-2-2
 * File Name : database.magike_mysql.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class MagikeMysql extends MagikeObject
{
	private $resource;
	private $dbLink;

	function __construct()
	{
		mgTrace();
		$this->dbLink = @mysql_connect(__DBHOST__,__DBUSER__,__DBPASS__) or $this->databaseException('Connect Error');
		mgTrace(false);
		mgDebug('Database Connect',__DBHOST__);
		@mysql_select_db(__DBNAME__,$this->dbLink) or $this->databaseException('Database Not Exists');
		$this->query('SET NAMES "utf8"');
		$this->resource = false;
	}
	
	public function getInsertId($dbLink = NULL)
	{
		$dbLink = $dbLink ? $dbLink : $this->dbLink;
		return mysql_insert_id($dbLink);
	}
	
	public function getAffectedRows($dbLink = NULL)
	{
		$dbLink = $dbLink ? $dbLink : $this->dbLink;
		return mysql_affected_rows($dbLink);
	}
	
	public function getRowsNumber($resource = NULL)
	{
		$resource = $resource ? $resource : $this->resource;
		return mysql_num_rows($resource);
	}
	
	public function databaseException($query)
	{
		$this->throwException(E_DATABASE,__DEBUG__ ? $query."<br />\n".mysql_error() : NULL);
	}
	
	public function query($query,$op = 'r')
	{
		$this->resource = false;
	
		mgTrace();
		$this->resource =  mysql_query($query) or $this->databaseException($query);
		mgTrace(false);
		mgDebug('Excute SQL',$query);

		return $this->resource;
	}
	
	public function fetchArray($resource = NULL)
	{
		$resource = $resource ? $resource : $this->resource;
		return mysql_fetch_assoc($resource);
	}
}
?>
