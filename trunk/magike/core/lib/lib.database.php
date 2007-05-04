<?php
/**********************************
 * Created on: 2007-2-2
 * File Name : lib.database.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
//连接数据库
define('E_DATABASE','Database Error');
$dblink=@mysql_connect(__DBHOST__,__DBUSER__,__DBPASS__) or die('Database Connect Error');
@mysql_select_db(__DBNAME__,$dblink) or die('Database Connect Error');
mysql_query('SET NAMES "utf8"') or die('Database Connect Error');

class Database extends MagikeObject
{
	private $usedTable;
	
	function __construct()
	{
		parent::__construct();
		$this->usedTable = array();
	}
	
	private function praseWhereSentence($args)
	{
		//处理where子句
		$where = '';
		if(isset($args['where']['template']))
		{
			$where = $args['where']['template'];
			if(isset($args['where']['value']))
			{
			$args['where']['value'] = self::filterQuotesSentence($args['where']['value']);

			while(($pos = strpos($where,'?')) != false)
			{
				if(isset($args['where']['value']) && $args['where']['value'] != NULL)
				{
					$value = array_shift($args['where']['value']);
					$where = substr_replace($where,$value,$pos,1);
				}
			}
			}

			$where = ' WHERE '.$where;
		}

		return $where;
	}

	private function filterQuotesSentence($value)
	{
		foreach($value as $key => $val)
		{
			$val = str_replace("'","''",$val);
			$val = (is_numeric($val) && (strlen(intval($val)) == strlen($val) || strlen(floatval($val)) == strlen($val))) ? $val : "'".$val."'";
			$value[$key] = $val;
		}

		return $value;
	}

	private function filterTablePrefix($args)
	{
		$this->usedTable = array();
		foreach($args as $key => $val)
		{
			if(is_string($val))
			{
				$args[$key] = preg_replace_callback("/([\ ,\(\)])table\.([0-9a-zA-Z-]+)/i",array($this,'praseTablePrefix'),' '.$val);
			}
		}

		if(isset($args['where']['template']))
		{
			$args['where']['template'] = preg_replace_callback("/([\ ,\(\)])table\.([_0-9a-zA-Z-]+)/i",array($this,'praseTablePrefix'),' '.$args['where']['template']);
		}
		
		return $args;
	}
	
	private function praseTablePrefix($matches)
	{
		$this->usedTable[] = __DBPREFIX__.$matches[2];
		return $matches[1].__DBPREFIX__.$matches[2];
	}

	protected function databaseException()
	{
		$this->throwException(E_DATABASE,__DEBUG__ ? mysql_error() : NULL);
	}

 	public function fectch($args,$callback = NULL,$expection = false)
 	{
 		//替换查询前缀
 		$args = $this->filterTablePrefix($args);

 		//处理table子句
		$table = isset($args['table']) && NULL !== $args['table'] ? ' FROM '.$args['table'] : '';

		//处理groupby子句
		$groupby = isset($args['groupby']) && NULL !== $args['groupby'] ? ' GROUP BY '.$args['groupby'] : '';

		//处理fields子句
		$fields = isset($args['fields']) && NULL !== $args['fields'] ? 'SELECT '.$args['fields'] : 'SELECT *';

		//处理order子句
		$orderby = isset($args['orderby']) && NULL !== $args['orderby'] ? ' ORDER BY '.$args['orderby'] : '';
		
		//处理sort子句
		$orderby = isset($args['orderby']) && isset($args['sort']) && NULL !== $args['orderby'] && NULL !== $args['sort'] ? $orderby.' '.$args['sort'] : '';

		//处理limit子句
		$limit = isset($args['limit']) && NULL !== $args['limit'] ? $args['limit'] : '';

		//处理offset子句
		$offset = isset($args['limit']) && isset($args['offset']) && NULL !== $args['limit'] && NULL !== $args['offset'] ? $args['offset'].',' : '';
		$offset = isset($args['limit']) && NULL !== $args['limit'] ? ' LIMIT '.$offset : '';
		
		//处理where子句
		$where = self::praseWhereSentence($args);
		$query = $fields.$table.$where.$groupby.$orderby.$offset.$limit;
		$resource = mysql_query($query) or $this->databaseException();
		$sum = mysql_num_rows($resource);
		$result = array();
		$num = 0;

		//开始输出
		while($rows = mysql_fetch_array($resource,MYSQL_ASSOC))
		{
			if(isset($callback['function']))
			{
				$callback['data'] = isset($callback['data']) ? $callback['data'] : NULL;
				$last = ($num == $sum - 1) ? true : false;
				$rows = call_user_func($callback['function'],$rows,$num,$last,$callback['data']);
			}

            $result[$num] = $rows;
            $num++;
		}

		if($num == 0 && $expection) $this->throwException(E_PATH_PATHNOTEXISTS,$this->stack['action']['path']);
		return $result;
 	}
 	
 	public function fectchOne($args,$callback = NULL,$expection = false)
 	{
 		$result = $this->fectch($args,$callback,$expection);
 		return $result ? $result[0] : array();
 	}

 	public function update($args)
 	{
 		$args['value'] = self::filterQuotesSentence($args['value']);
 		//替换查询前缀
 		$args = $this->filterTablePrefix($args);
 		$table = 'UPDATE '.$args['table'];
 		$value = ' SET ';
 		$columns = array();

 		if(isset($args['value']))
 		{
 			foreach($args['value'] as $key => $val)
 			{
				array_push($columns,"$key = $val");
 			}
 		}
 		$value = $value.implode(' , ',$columns);
		$where = self::praseWhereSentence($args);
		mysql_query($table.$value.$where) or $this->databaseException();
		return mysql_affected_rows();
 	}
 	
 	public function increaseField($args,$field,$num = 1)
 	{
 		$args = $this->filterTablePrefix($args);
 		$table = 'UPDATE '.$args['table'];
 		$increase = " SET {$field} = {$field} + {$num}";
 		$where = self::praseWhereSentence($args);
 		mysql_query($table.$increase.$where) or $this->databaseException();
 		return mysql_affected_rows();
 	}
 		
 	public function decreaseField($args,$field,$num = 1)
 	{
 		$args = $this->filterTablePrefix($args);
 		$table = 'UPDATE '.$args['table'];
 		$increase = " SET {$field} = {$field} - {$num}";
 		$where = self::praseWhereSentence($args);
 		mysql_query($table.$increase.$where) or $this->databaseException();
 		return mysql_affected_rows();
 	}

 	public function insert($args)
 	{
 		$args['value'] = self::filterQuotesSentence($args['value']);
 		//替换查询前缀
 		$args = $this->filterTablePrefix($args);
		$query = 'INSERT INTO '.$args['table'].' ('.implode(',',array_keys($args['value'])).') VALUES('.implode(',',$args['value']).')';
		mysql_query($query) or $this->databaseException();
		return mysql_insert_id();
 	}

 	public function delete($args)
 	{
 		//替换查询前缀
 		$args = $this->filterTablePrefix($args);
 		$table = 'DELETE FROM '.$args['table'];
		$where = self::praseWhereSentence($args);
		mysql_query($table.$where) or $this->databaseException();
		return mysql_affected_rows();
 	}

 	public function count($args)
 	{
 		//替换查询前缀
 		$args = $this->filterTablePrefix($args);
		$fields = 'SELECT COUNT('.(isset($args['key']) ? $args['key'] : 'id').') AS number';
 		$table = ' FROM '.$args['table'];
		$groupby = isset($args['groupby']) ? ' GROUP BY '.$args['groupby'] : '';
 		$where = self::praseWhereSentence($args);

 		$resource = mysql_query($fields.$table.$where.$groupby) or $this->databaseException();
 		$result = mysql_fetch_array($resource,MYSQL_ASSOC);
 		return $result['number'];
 	}
}
?>
