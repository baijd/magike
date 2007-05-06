<?php
/**********************************
 * Created on: 2007-2-2
 * File Name : validator.add_comment_filter.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

$elements = array(
	"comment_filter_name" => array("null" => "您提交的过滤器名称不能为空"),
	"comment_filter_type" => array("null" => "您必须为过滤器选择一个适用范围",
								   "enum(ping,comment,all)" => "您提交的适用范围不合法"),
);
?>
