<?php
/**********************************
 * Created on: 2007-2-2
 * File Name : lib.magike_object.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
 class MagikeModel extends Database
 {
 	 private $table;
 	 protected $key;
 	 
 	 function __construct($table = NULL)
 	 {
 	 	 parent::__construct();
 	 	 if(!$this->table)
 	 	 {
 	 	 	 $this->table = str_replace('_model','',mgClassNameToFileName(get_class($this)));
 	 	 }
 	 	 $this->table = str_replace('table.',__DBPREFIX__,$table);
 	 	 $this->key = $this->findPrimaryKey();
 	 }
 	 
 	 private findPrimaryKey()
 	 {
 	 	 $res = mysql_query("SHOW INDEX FROM {$this->table} WHERE Key_name = 'PRIMARY'") or $this->databaseException();
 	 	 $row = mysql_fetch_array($res);
 	 	 return $row ? $row['Column_name'] : NULL;
 	 }
 	 
 	 protected deleteByKeys($keys)
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
 	 
 	 protected fectchByKey($key)
 	 {
 	 	 return $this->fectchOne(array('table' => $this->table,
 	 	 							   'where' => array('template' => "{$this->key} = ?",
 	 	 												'value' => array($key))));
 	 }
 	 
 	 protected increaseFieldByKey($key,$field,$num = 1)
 	 {
 	 	 return $this->increaseField(array('table' => $this->table,
 	 	 								   'where' => array('template' => "{$this->key} = ?",
 	 	 													'value' => array($key))),
 	 	 							$field,$num);
 	 }
 	 
 	 protected decreaseFieldByKey($key,$field,$num = 1)
 	 {
 	 	 return $this->decreaseField(array('table' => $this->table,
 	 	 								   'where' => array('template' => "{$this->key} = ?",
 	 	 													'value' => array($key))),
 	 	 							$field,$num);
 	 }
 }
 ?>
 