<?php
/**********************************
 * Created on: 2007-2-2
 * File Name : validator.setting_post.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

$elements = array(
	"post_date_format" => array("null" => "必须填写日期格式"),
	"post_sub" => array("null" => "必须填写摘要字数" , "num" => "摘要字数必须为数字"),
	"post_page_num" => array("null" => "必须填写分页文章数" , "num" => "分页文章数必须为数字"),
	"post_list_num" => array("null" => "必须填写列表文章数" , "num" => "列表文章数必须为数字"),
);
?>
