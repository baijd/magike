<?php
/**********************************
 * Created on: 2006-11-30
 * File Name : database_model.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class DatabaseModel extends MagikeObject
{
 	function __construct()
 	{
		$dblink=@mysql_connect(__DBHOST__,__DBUSER__,__DBPASS__) or $this->databaseException();
		@mysql_select_db(__DBNAME__,$dblink) or $this->databaseException();
 		mysql_query('SET NAMES "utf8"') or $this->databaseException();
 		//获取实例化的stack
		parent::__construct(array('public' => array('stack')));
		$this->stack->setStack('system','query_times',0);
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
		foreach($args as $key => $val)
		{
			if(is_string($val))
			{
				$args[$key] = preg_replace("/([\ ,\(\)])table\./i","\\1".__DBPREFIX__,' '.$val);
			}
		}

		if(isset($args['where']['template']))
		{
			$args['where']['template'] = preg_replace("/([\ ,\(\)])table\./i","\\1".__DBPREFIX__,' '.$args['where']['template']);
		}

		return $args;
	}

	private function databaseException()
	{
		$this->throwException(E_DATABASE,mysql_error(),array('MagikeAPI','errorDatabaseCallback'));
	}

 	public function fectch($args,$callback = NULL,$expection = false)
 	{
 		//设定查询次数
 		$this->stack->setStack('system','query_times',$this->stack->data['system']['query_times'] + 1);

 		//替换查询前缀
 		$args = $this->filterTablePrefix($args);

 		//处理table子句
		$table = isset($args['table']) ? ' FROM '.$args['table'] : '';

		//处理groupby子句
		$groupby = isset($args['groupby']) ? ' GROUP BY '.$args['groupby'] : '';

		//处理fields子句
		$fields = isset($args['fields']) ? 'SELECT '.$args['fields'] : 'SELECT *';

		//处理order子句
		$orderby = isset($args['orderby']) ? ' ORDER BY '.$args['orderby'] : '';

		//处理limit子句
		$limit = isset($args['limit']) ? ' LIMIT '.$args['limit'] : '';

		//处理offset子句
		$offset = isset($args['limit']) && isset($args['offset']) ? ','.$args['offset'] : '';

		//处理where子句
		$where = self::praseWhereSentence($args);
		$query = $fields.$table.$where.$groupby.$orderby.$limit.$offset;
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

		if($num == 0 && $expection) $this->throwException(E_DATABASE,NULL,array('MagikeAPI','error404Callback'));
		return $result;
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

 	public function insert($args)
 	{
 		$args['value'] = self::filterQuotesSentence($args['value']);
 		//替换查询前缀
 		$args = $this->filterTablePrefix($args);
		$query = 'INSERT INTO '.$args['table'].' ('.implode(',',array_keys($args['value'])).') VALUES('.implode(',',$args['value']).')';
		mysql_query($query) or $this->databaseException();
		return mysql_affected_rows();
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
