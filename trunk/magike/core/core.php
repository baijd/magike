<?php
/**********************************
 * Created on: 2007-2-8
 * File Name : core.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
define('__DIR__',dirname(__FILE__));			//框架绝对路径
require(__DIR__.'/core.config.php');
require(__CONFIG__);
require(__DIR__.'/core.functions.php');
require(__DIR__.'/core.init.php');

//初始化动作
require(__DIR__.'/action/action.php');
new Action();
?>
