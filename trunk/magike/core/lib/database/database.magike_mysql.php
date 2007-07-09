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
	private $lastQuery;
	private $dbLink;
	private $cacheLink;
	private $cacheOpen;
	private $cacheResource;
	private $cacheExpire;
	private $cachePos;

	function __construct()
	{
		mgTrace();
		$this->dbLink = @mysql_connect(__DBHOST__,__DBUSER__,__DBPASS__) or $this->databaseException('Connect Error');
		mgTrace(false);
		mgDebug('Database Connect',__DBHOST__);
		@mysql_select_db(__DBNAME__,$this->dbLink) or $this->databaseException('Database Not Exists');
		$this->query('SET NAMES "utf8"');
		$this->cacheOpen = false;
		$this->resource = false;
		$this->cacheExpire = false;
		
		if(__SQL_CACHE__)
		{
			$this->cacheLink = new Memcache();
			$this->cacheLink->connect(__SQL_CACHE_SERVER__, __SQL_CACHE_PORT__) or $this->databaseException('Memcache Server Connect Error');
		}
	}
	
	public function beginCache($expire = 60)
	{
		$this->cacheExpire = $expire;
		$this->cacheOpen = __SQL_CACHE__;
	}
	
	public function endCache()
	{
		$this->cacheOpen = false;
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
		
		if(is_resource($resource))
		{
			return mysql_num_rows($resource);
		}
		else
		{
			return $this->cacheLink->get($resource);
		}
	}
	
	public function databaseException($query)
	{
		$this->throwException(E_DATABASE,__DEBUG__ ? $query."<br />\n".mysql_error() : NULL);
	}
	
	public function query($query,$op = 'r')
	{
		$this->lastQuery = $query;
		$this->resource = false;
		$this->cachePos = 0;
		$recordNum = false;
	
		mgTrace();
		
		if($this->cacheOpen && 'r' == $op)
		{
			$this->resource =  'sql-'.md5($query);
			$recordNum = $this->cacheLink->get($this->resource);
		}
		if(false === $recordNum)
		{
			$this->resource =  mysql_query($query) or $this->databaseException($query);
			if($this->cacheOpen && 'r' == $op)
			{
				$this->cacheLink->set('sql-'.md5($query), mysql_num_rows($this->resource),false,$this->cacheExpire) or $this->databaseException('Memcache Set Error');
			}
		}
		
		mgTrace(false);
		mgDebug('Excute SQL',$query);

		return $this->resource;
	}
	
	public function fetchArray($resource = NULL)
	{
		$resource = $resource ? $resource : $this->resource;
		if(!is_resource($resource))
		{
			$result = $this->cacheLink->get($resource.'-'.$this->cachePos);
			$this->cachePos ++;
			return $result;
		}
		else
		{
			$result = mysql_fetch_array($resource,MYSQL_ASSOC);
			if($this->cacheOpen && $result)
			{
				$this->cacheLink->set('sql-'.md5($this->lastQuery).'-'.$this->cachePos, $result,false,$this->cacheExpire) or $this->databaseException('Memcache Set Error');
				$this->cachePos ++;
			}
			return $result;
		}
	}
}
?>
