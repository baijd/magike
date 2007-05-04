<?php
/**********************************
 * Created on: 2007-2-2
 * File Name : lib.magike_object.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class MagikeModel extends Database
{
 	 protected $table;
 	 protected $key;
 	 protected $group;
 	 
 	 function __construct($table = NULL)
 	 {
 	 	 parent::__construct();
 	 	 if(!$table)
 	 	 {
 	 	 	 $table = 'table.'.str_replace('_model','',mgClassNameToFileName(get_class($this)));
 	 	 }
 	 	 $this->table = str_replace('table.',__DBPREFIX__,$table);
 	 	 $this->group = NULL;
 	 	 $this->key = $this->findPrimaryKey();
 	 }
 	 
 	 private function findPrimaryKey()
 	 {
 	 	 $res = mysql_query("SHOW INDEX FROM {$this->table} WHERE Key_name = 'PRIMARY'") or $this->databaseException();
 	 	 $row = mysql_fetch_array($res);
 	 	 return $row ? $this->table.".".$row['Column_name'] : NULL;
 	 }
 	 
 	 public function deleteByKeys($keys)
 	 {
 	 	 $sum = 0;
 	 	 if(is_string($keys))
 	 	 {
 	 	 	 $keys = explode(',',$keys);
 	 	 }
 	 	 
 	 	 foreach($keys as $key)
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
 	 	 return $sum;
 	 }
 	 
 	 public function deleteByFieldEqual($field,$value)
 	 {
 			return  $this->delete(array('table' => $this->table,
 										'where' => array('template' => "{$field} = ?",
 										 'value' => array($value)
 					)));
 	 }
 	 
 	 public function fectchByKey($key)
 	 {
 	 	 return $this->fectchOne(array('table' => $this->table,
 	 	 	 						   'groupby' => $this->group,
 	 	 							   'where' => array('template' => "{$this->key} = ?",
 	 	 												'value' => array($key))));
 	 }
 	 
 	 public function fectchByFieldEqual($field,$value)
 	 {
 	 	 return $this->fectchOne(array('table' => $this->table,
 	 	 	 						   'groupby' => $this->group,
 	 	 							   'where' => array('template' => "{$field} = ?",
 	 	 												'value' => array($value))));
 	 }
 	 
 	 public function fectchByFieldLike($field,$value)
 	 {
 	 	 return $this->fectch(array('table' => $this->table,
 	 	 	 						   'groupby' => $this->group,
 	 	 							   'where' => array('template' => "{$field} LIKE ?",
 	 	 												'value' => array($value))));
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
 	 
 	 public function countTable($args)
 	 {
 	 	 $args['key'] = isset($args['key']) ? $args['key'] : $this->key;
 	 	 $args['table'] = isset($args['table']) ? $args['table'] : $this->table;
 	 	 return $this->count($args);
 	 }
}
?>
 