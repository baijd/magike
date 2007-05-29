<?php
/**********************************
 * Created on: 2007-2-2
 * File Name : validator.write_post.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

$elements = array(
	"comment_email" => array("mail" => "电子邮件格式错误"),
	"comment_homepage" => array("url" => "个人主页地址格式错误"),
	"comment_text" => array("null" => "内容不能为空")
);

if($this->stack['static_var']['comment_email_notnull'] && !$this->stack['access']['login'])
{
	$elements["comment_email"]["null"] = "必须填写电子邮件";
}
if($this->stack['static_var']['comment_homepage_notnull'] && !$this->stack['access']['login'])
{
	$elements["comment_homepage"]["null"] = "必须填写个人主页";
}
if(!$this->stack['access']['login'])
{
	$elements["comment_user"]["null"] = "用户名不能为空";
}
?>
