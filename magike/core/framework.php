<?php
/**********************************
 * Created on: 2006-12-1
 * File Name : framework.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

//定义常量
define('__DIR__',dirname(__FILE__));			//框架绝对路径
define('__COMPILE__','./data/compile');			//模板编译目录
define('__CACHE__','./data/cache');				//定义缓存目录
define('__MOD__','./module');					//定义模块目录
define('__TEMPLATE__','./templates');			//定义模板目录
define('__LANGUAGE__','./data/language');		//定义语言目录
define('__UPLOAD__','./data/upload');			//定义上传目录
define('__CONFIG__','./config.php');			//定义初始配置文件
define('__DEBUG__',false);						//是否开启debug模式
define('__GZIP__',false);						//是否开启gzip模式

//载入核心
include(__CONFIG__);							//载入配制文件
include(__DIR__.'/kernel/magike_api.php');		//载入通用编程接口
include(__DIR__.'/kernel/magike_exception.php');//载入异常处理
include(__DIR__.'/kernel/magike_object.php');	//载入基类
include(__DIR__.'/kernel/magike.php');			//载入系统

//载入静态模型
include(__DIR__.'/model/stack_model.php');		//载入堆栈模型
include(__DIR__.'/model/static_model.php');		//载入静态变量模型

new Magike();
?>
