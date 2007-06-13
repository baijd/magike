<?php
/**********************************
 * Created on: 2007-2-8
 * File Name : core.config.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

//定义常量

//环境路径配置
define('__COMPILE__','./data/compile');			//模板编译目录
define('__CACHE__','./data/cache');			//定义缓存目录
define('__MODULE__','./module');				//定义模块目录
define('__MODEL__','./model');				//定义模型目录
define('__TEMPLATE__','./templates');			//定义模板目录
define('__LANGUAGE__','./language');			//定义语言目录
define('__UPLOAD__','./data/upload');			//定义上传目录
define('__CONFIG__','./config.php');				//定义初始配置文件
define('__LIB__',__DIR__.'/lib');				//定义库文件目录

//调试相关
define('__DEBUG__',false);					//是否开启debug模式
define('__DEBUG_LOG__','./data/debug.log');		//debug信息输出路径
define('__DEBUG_SORT_BY_TIME__',false);		//是否按照时间排序
define('__DEBUG_TIME_FILTER__',0);			//时间过滤器
define('__DEBUG_MESSAGE_FILTER__',NULL);		//消息过滤器

//缓存相关
define('__GZIP__',false);							//是否开启gzip模式
define('__HTML_CACHE__','./data/html');					//静态页面缓存存放地址
define('__SQL_CACHE__',false);						//是否开启sql缓存
define('__SQL_CACHE_SOURCE__','127.0.0.1:24@memcache');	//sql缓存数据源
?>
