<?php
/**********************************
 * Created on: 2007-2-2
 * File Name : validator.write_post.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

$elements = array(
	"post_title" => array("null" => "标题必须填写"),
	"post_content" => array("null" => "内容不能为空"),
	"post_name" => array("modelIngore(posts,checkPostNameExists,".(isset($_POST['post_id']) && $_POST['post_id'] ?  $_POST['post_id'] : 0).")" => "静态地址已经存在"),
);
?>
