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

	private function praseWhereSentence($args)
	{
		//处理where子句
		$where = '';
		if(isset($args['where']['template']))
		{
			$where = $args['where']['template'];
			while(($pos = strpos($where,'?')) != false)
			{
				if($value = array_shift($args['where']['value']))
				{
					$value = is_numeric($value) ? $value : "'".$value."'";
					$where = substr_replace($where,$value,$pos,1);
				}
			}

			$where = ' WHERE '.$where;
		}

		return $where;
	}

 	public function fectch($args,$callback = NULL,$expection = false)
 	{
 		//设定查询次数
 		$times = $this->stackPop('system','query_times');
 		$this->stackPush('system','query_times',$times + 1);

 		//处理table子句
		$table = ' FROM '.$args['table'];

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
		$resource = mysql_query($query) or die($this->_exception(E_DATABASE,mysql_error(),'errorDatabaseCallback'));
		$result = array();
		$num = 0;

		//开始输出
		while($rows = mysql_fetch_array($resource,MYSQL_ASSOC))
		{
			if(isset($callback['function']))
			{
				$callback['data'] = isset($callback['data']) ? $callback['data'] : NULL;
				$rows = call_user_func($callback['function'],$rows,$num,$callback['data']);
			}

            $result[$num] = $rows;
            $num++;
		}

		if($num == 0 && $expection) die($this->_exception(E_DATABASE,NULL,'error404Callback'));
		return $result;
 	}

 	public function update($args)
 	{
 		$table = 'UPDATE '.$args['table'];
 		$value = ' SET ';
 		$columns = array();

 		if(isset($args['value']))
 		{
 			foreach($args['value'] as $key => $val)
 			{
				array_push($columns,'$key = $val');
 			}
 		}
 		$value = $value.implode(' AND ',$columns);
		$where = self::praseWhereSentence($args);

		mysql_query($table.$value.$where) or die($this->_exception(E_DATABASE,mysql_error(),'errorDatabaseCallback'));
		return mysql_affected_rows();
 	}

 	public function insert($args)
 	{
		$query = 'INSERT INTO '.$args['table'].' ('.implode(',',array_keys($args['value'])).') VALUES('.implode(',',$args['value']).')';
		mysql_query($query) or die($this->_exception(E_DATABASE,mysql_error(),'errorDatabaseCallback'));
		return mysql_affected_rows();
 	}

 	public function delete($args)
 	{
 		$table = 'DELETE FROM '.$args['table'];
		$where = self::praseWhereSentence($args);
		mysql_query($table.$where) or die($this->_exception(E_DATABASE,mysql_error(),'errorDatabaseCallback'));
		return mysql_affected_rows();
 	}

 	public function count($args)
 	{
 		$fields = 'SELECT COUNT('.$args['key'].') AS number';
 		$table = ' FROM '.$args['table'];
		$groupby = isset($args['groupby']) ? ' GROUP BY '.$args['groupby'] : '';
 		$where = self::praseWhereSentence($args);

 		$resource = mysql_query($fields.$table.$where.$groupby) or die($this->_exception(E_DATABASE,mysql_error(),'errorDatabaseCallback'));
 		$result = mysql_fetch_array($resource,MYSQL_ASSOC);
 		return $result['number'];
 	}
}
?>
