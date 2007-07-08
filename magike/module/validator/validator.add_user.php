<?php
/**********************************
 * Created on: 2007-2-2
 * File Name : validator.add_user.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

$elements = array(
	"user_name" => array("null" => "用户名称不能为空","model(users,checkUserExists)" => "用户名已经存在"),
	"user_mail" => array("model(users,checkMailExists)" => "电子邮件已经存在"),
	"user_password_confrm" => array("confrm(user_password)" => "两次输入密码不一致"),
	"user_url" => array("url" => "您提交的链接地址格式不合法"),
	"user_mail" => array("mail" => "您提交的电子邮件地址格式不合法")
);
?>
 