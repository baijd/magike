<?php
/**********************************
 * Created on: 2007-2-2
 * File Name : lib.database.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

require(__LIB__.'/database/database.'.__DBOBJECT__.'.php');
class Database extends MagikeObject
{
	private $usedTable;
	private $dbObject;
	
	function __construct()
	{
		$dbObjectName = mgFileNameToClassName(__DBOBJECT__);
		parent::__construct(array('public' => array($dbObjectName)));
		eval('$this->dbObject = $this->'.$dbObjectName.';');
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
		$result = array();
		
		if(NULL == $value)
		{
			return "''";
		}
		
		foreach($value as $key => $val)
		{
			if('@' != $key[0])
			{
				$val = str_replace("'","''",$val);
				$val = (is_numeric($val) && (strlen(intval($val)) == strlen($val) || strlen(floatval($val)) == strlen($val))) ? $val : "'".$val."'";
				$result[$key] = $val;
			}
			else
			{
				$result[substr($key,1,strlen($key)-1)] = $val;
			}
		}

		return $result;
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

 	public function fetch($args,$callback = NULL,$expection = false)
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
		$orderby = isset($args['orderby']) && NULL !== $args['orderby'] ? ' ORDER BY '.$args['orderby'] : 'ORDER BY';
		
		//处理sort子句
		$orderby = isset($args['sort']) && NULL !== $args['sort'] ? $orderby.' '.$args['sort'] : '';

		//分析limit
		if(isset($args['limit']))
		{
			$args['limit'] = intval($args['limit']);
			if($args['limit'] < 0)
			{
				return array();
			}
		}

		//处理limit子句
		$limit = isset($args['limit']) && NULL !== $args['limit'] ? $args['limit'] : '';
		
		//分析offset
		if(isset($args['offset']))
		{
			$args['offset'] = intval($args['offset']);
			if($args['offset'] < 0)
			{
				return array();
			}
		}
		
		//处理offset子句
		$offset = isset($args['limit']) && isset($args['offset']) && NULL !== $args['limit'] && NULL !== $args['offset'] ? $args['offset'].',' : '';
		$offset = isset($args['limit']) && NULL !== $args['limit'] ? ' LIMIT '.$offset : '';
		
		//处理where子句
		$where = self::praseWhereSentence($args);
		$query = $fields.$table.$where.$groupby.$orderby.$offset.$limit;
		$resource = $this->dbObject->query($query,'r');
		$sum = $this->dbObject->getRowsNumber($resource);
		$result = array();
		$num = 0;

		//开始输出
		while($rows = $this->dbObject->fetchArray($resource))
		{
			if(isset($callback['function']))
			{
				$callback['data'] = isset($callback['data']) ? $callback['data'] : NULL;
				$last = ($num == $sum - 1) ? true : false;
				$rows = call_user_func($callback['function'],$rows,$num,$last,$callback['data']);
			}
			
			if(false !== $rows)
			{
            			$result[$num] = $rows;
            			$num++;
		}
		}

		if($num == 0 && $expection) $this->throwException(E_PATH_PATHNOTEXISTS,$this->stack['action']['path']);
		return $result;
 	}
 	
 	public function fetchOne($args,$callback = NULL,$expection = false)
 	{
 		$args['limit'] = 1;
 		$result = $this->fetch($args,$callback,$expection);
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
				$columns[] = "$key = $val";
 			}
 		}
 		$value = $value.implode(' , ',$columns);
		$where = self::praseWhereSentence($args);
		$query = $table.$value.$where;
		$this->dbObject->query($query,'w');
		return $this->dbObject->getAffectedRows();
 	}
 	
 	public function increaseField($args,$field,$num = 1)
 	{
 		$args = $this->filterTablePrefix($args);
 		$table = 'UPDATE '.$args['table'];
 		$increase = " SET {$field} = {$field} + {$num}";
 		$where = self::praseWhereSentence($args);
 		$this->dbObject->query($table.$increase.$where,'w');
 		return $this->dbObject->getAffectedRows();
 	}
 		
 	public function decreaseField($args,$field,$num = 1)
 	{
 		$args = $this->filterTablePrefix($args);
 		$table = 'UPDATE '.$args['table'];
 		$increase = " SET {$field} = {$field} - {$num}";
 		$where = self::praseWhereSentence($args);
 		$this->dbObject->query($table.$increase.$where,'w');
 		return $this->dbObject->getAffectedRows();
 	}

 	public function insert($args)
 	{
 		$args['value'] = self::filterQuotesSentence($args['value']);
 		//替换查询前缀
 		$args = $this->filterTablePrefix($args);
		$query = 'INSERT INTO '.$args['table'].' ('.implode(',',array_keys($args['value'])).') VALUES('.implode(',',$args['value']).')';
		$this->dbObject->query($query,'w');
		return $this->dbObject->getInsertId();
 	}

 	public function delete($args)
 	{
 		//替换查询前缀
 		$args = $this->filterTablePrefix($args);
 		$table = 'DELETE FROM '.$args['table'];
		$where = self::praseWhereSentence($args);
		$query = $table.$where;
		$this->dbObject->query($query,'w');
		return $this->dbObject->getAffectedRows();
 	}

 	public function count($args)
 	{
 		//替换查询前缀
 		$args = $this->filterTablePrefix($args);
		$fields = 'SELECT COUNT('.(isset($args['key']) ? $args['key'] : 'id').') AS number';
 		$table = ' FROM '.$args['table'];
		$groupby = isset($args['groupby']) ? ' GROUP BY '.$args['groupby'] : '';
 		$where = self::praseWhereSentence($args);
		
		$query = $fields.$table.$where.$groupby;
 		$resource = $this->dbObject->query($query,'r');
 		$result = $this->dbObject->fetchArray($resource);
 		return $result['number'];
 	}
 	
 	public function sum($args)
 	{
  		//替换查询前缀
 		$args = $this->filterTablePrefix($args);
		$fields = 'SELECT SUM('.(isset($args['key']) ? $args['key'] : 'id').') AS number';
 		$table = ' FROM '.$args['table'];
		$groupby = isset($args['groupby']) ? ' GROUP BY '.$args['groupby'] : '';
 		$where = self::praseWhereSentence($args);

		$query = $fields.$table.$where.$groupby;
 		$resource = $this->dbObject->query($query,'r');
 		$result = $this->dbObject->fetchArray($resource);
 		return $result['number'];
 	}
	
	public function query($query,$op = 'r')
	{
		$result =  $this->dbObject->query($query,$op);
		return $result;
	}
}
?>
