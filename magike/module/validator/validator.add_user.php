<?php
/**********************************
 * Created on: 2007-2-2
 * File Name : validator.add_user.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

$elements = array(
	"user_name" => array("null" => "用户名称不能为空","modelIngore(users,checkUserExistsIgnore,".(isset($_POST['user_id']) && $_POST['user_id'] ?  $_POST['user_id'] : 0).")" => "用户名已经存在"),
	"user_mail" => array("mail" => "您提交的电子邮件地址格式不合法","modelIngore(users,checkMailExistsIgnore,".(isset($_POST['user_id']) && $_POST['user_id'] ?  $_POST['user_id'] : 0).")" => "电子邮件已经存在"),
	"user_password_confrm" => array("confrm(user_password)" => "两次输入密码不一致"),
	"user_url" => array("url" => "您提交的链接地址格式不合法")
);
?>
 