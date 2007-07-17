<?php
/**********************************
 * Created on: 2007-2-2
 * File Name : lib.magike_object.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
abstract class MagikeModel extends Database
{
 	 protected $table;
 	 protected $key;
 	 protected $group;
 	 protected $orderby;
 	 protected $sort;
 	 
 	 function __construct($table = NULL)
 	 {
 	 	 parent::__construct();
 	 	 if(!$table)
 	 	 {
 	 	 	 $table = 'table.'.str_replace('_model','',mgClassNameToFileName(get_class($this)));
 	 	 }
 	 	 $this->table = str_replace('table.',__DBPREFIX__,$table);
 	 	 $this->clearArgs();
 	 	 $this->key = $this->findPrimaryKey();
 	 }
 	 
 	 private function findPrimaryKey()
 	 {
 	 	 $res = $this->query("SHOW INDEX FROM {$this->table} WHERE Key_name = 'PRIMARY'");
 	 	 $row = mysql_fetch_array($res);
 	 	 return $row ? $this->table.".".$row['Column_name'] : NULL;
 	 }
 	 
 	 protected function clearArgs()
 	 {
 	 	 $this->group = NULL;
 	 	 $this->orderby = NULL;
 	 	 $this->sort = NULL;
 	 }
 	 
 	 public function deleteByKeys($keys,$except = array())
 	 {
 	 	 $sum = 0;
 	 	 if(!is_array($keys))
 	 	 {
 	 	 	 $keys = explode(',',$keys);
 	 	 }
 	 	 
 	 	 foreach($keys as $key)
 	 	 {
 	 	 	if(!$except || !in_array($key,$except))
 	 	 	{
	 	 	 	$result = 
	 	 	 	$this->delete(array('table' => $this->table,
	 	 	 						'where' => array('template' => "{$this->key} = ?",
	 	 	 										 'value' => array($key)
	 	 	 			)));
	 	 	 	if($result)
	 	 	 	{
	 	 	 		$sum ++;
	 	 	 	}
 	 	 	}
 	 	 }
 	 	 return $sum;
 	 }
 	 
 	 public function deleteByFieldEqual($field,$value)
 	 {
 			return  $this->delete(array('table' => $this->table,
 										'where' => array('template' => "{$field} = ?",
 										 'value' => array($value)
 					)));
 	 }
 	 
 	 public function fetchOneByKey($key,$callback = NULL,$expection = false)
 	 {
 	 	 return $this->fetchOne(array('table' => $this->table,
 	 	 	 						   'groupby' => $this->group,
 	 	 							   'where' => array('template' => "{$this->key} = ?",
 	 	 												'value' => array($key))),$callback,$expection);
 	 }
 	 
 	 public function fetchOneByFieldEqual($field,$value,$callback = NULL,$expection = false)
 	 {
 	 	 return $this->fetchOne(array('table' => $this->table,
 	 	 	 						   'groupby' => $this->group,
 	 	 							   'where' => array('template' => "{$field} = ?",
 	 	 												'value' => array($value))),$callback,$expection);
 	 }
 	 
 	 public function fetchOneByFieldLike($field,$value,$callback = NULL,$expection = false)
 	 {
 	 	 return $this->fetchOne(array('table' => $this->table,
 	 	 	 						   'groupby' => $this->group,
 	 	 							   'where' => array('template' => "{$field} LIKE ?",
 	 	 												'value' => array($value))),$callback,$expection);
 	 }
 	 
 	 public function fetchByFieldEqual($field,$value,$offset = false,$limit = false,$callback = NULL,$expection = false)
 	 {
 	 	 $args = array('table' => $this->table,
 	 	 	 		   'groupby' => $this->group,
 	 	 	 		   'orderby' => $this->orderby,
 	 	 	 		   'sort'	 => $this->sort,
 	 	 			   'where' => array('template' => "{$field} = ?",
 	 	 								'value' => array($value)));
 	 	 if(false !== $offset)
 	 	 {
 	 	 	 $args['offset'] = $offset;
 	 	 }
 	 	 if(false !== $limit)
 	 	 {
 	 	 	 $args['limit'] = $limit;
 	 	 }
 	 	 return $this->fetch($args,$callback,$expection);
 	 }
 	 
 	 public function fetchByFieldLike($field,$value,$offset = false,$limit = false,$callback = NULL,$expection = false)
 	 {
 	 	 $args = array('table' => $this->table,
 	 	 	 		   'groupby' => $this->group,
 	 	 	 		   'orderby' => $this->orderby,
 	 	 	 		   'sort'	 => $this->sort,
 	 	 			   'where' => array('template' => "{$field} LIKE ?",
 	 	 								'value' => array($value)));
 	 	 if(false !== $offset)
 	 	 {
 	 	 	 $args['offset'] = $offset;
 	 	 }
 	 	 if(false !== $limit)
 	 	 {
 	 	 	 $args['limit'] = $limit;
 	 	 }
 	 	 return $this->fetch($args,$callback,$expection);
 	 }
 	 
 	 public function increaseFieldByKey($key,$field,$num = 1)
 	 {
 	 	 return $this->increaseField(array('table' => $this->table,
 	 	 								   'where' => array('template' => "{$this->key} = ?",
 	 	 													'value' => array($key))),
 	 	 							$field,$num);
 	 }
 	 
 	 public function decreaseFieldByKey($key,$field,$num = 1)
 	 {
 	 	 return $this->decreaseField(array('table' => $this->table,
 	 	 								   'where' => array('template' => "{$this->key} = ?",
 	 	 													'value' => array($key))),
 	 	 							$field,$num);
 	 }
 	 
 	 public function insertTable($value)
 	 {
 	 	 return $this->insert(array('table' => $this->table,
									'value' => $value));
 	 }
 	 
 	 public function updateByKey($key,$value)
 	 {
 	 	 return $this->update(array('table' => $this->table,
						 	 	 	'where' => array('template' => "{$this->key} = ?",
						 	 	 					 'value' => array($key)),
									'value' => $value));
 	 }
 	 
 	 public function updateByFiledEqual($filed,$filedValue,$value)
 	 {
 	 	 return $this->update(array('table' => $this->table,
						 	 	 	'where' => array('template' => "{$filed} = ?",
						 	 	 					 'value' => array($filedValue)),
									'value' => $value));
 	 }
 	 
 	 public function countTable($args = array())
 	 {
 	 	 $args['key'] = isset($args['key']) ? $args['key'] : $this->key;
 	 	 $args['table'] = isset($args['table']) ? $args['table'] : $this->table;
 	 	 return $this->count($args);
 	 }
 	 
 	 public function sumTable($args = array())
 	 {
 	 	 $args['key'] = isset($args['key']) ? $args['key'] : $this->key;
 	 	 $args['table'] = isset($args['table']) ? $args['table'] : $this->table;
 	 	 return $this->sum($args);
 	 }
}
?>
