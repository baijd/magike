<?php
/**********************************
 * Created on: 2007-2-2
 * File Name : validator.register.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

$elements = array(
	"user_name" => array("null" => "必须填写用户名","model(users,checkUserExists)" => "用户名已经存在"),
	"user_mail" => array("null" => "必须填写电子邮件","mail" => "电子邮件格式不符合规范","model(users,checkMailExists)" => "电子邮件已经存在"),
	"user_url" => array("url" => "网址格式不符合规范"),
	"user_password" => array("null" => "必须填写密码"),
	"user_password_confrm" => array("null" => "必须确认密码","confrm(user_password)" => "两次输入密码不一致")
);
?>
