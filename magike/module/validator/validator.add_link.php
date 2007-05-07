<?php
/**********************************
 * Created on: 2007-2-2
 * File Name : validator.add_link.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

$elements = array(
	"link_name" => array("null" => "您提交的链接名称不能为空"),
	"link_url" => array("null" => "您提交链接地址不能为空","url" => "您提交链接地址格式不合法"),
	"link_category_id" => array("null" => "您必须选择一个链接分类"),
);
?>
 