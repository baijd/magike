<?php
header("content-Type: text/html; charset=UTF-8");
include("./core/core.functions.php");

function install_get_siteurl()
{
	$url = "http://".$_SERVER["HTTP_HOST"].$_SERVER["PHP_SELF"];
	if(isset($_SERVER["PATH_INFO"]))
	{
		$url = str_replace($_SERVER["PATH_INFO"],"",$url);
	}
	
	if(isset($_SERVER["QUERY_STRING"]))
	{
		$url = str_replace("?".$_SERVER["QUERY_STRING"],"",$url);
	}
	
	return dirname($url);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Magike 1.0RC1 安装程序</title>
	<link href="./templates/admin/style.css" rel="stylesheet" type="text/css" />
	<script>
		var templateUrl = "./templates/admin";
	</script>
	<script language="javascript" type="text/javascript" src="./templates/admin/javascript/jquery.js"></script>
	<script language="javascript" type="text/javascript" src="./templates/admin/javascript/magike_control.js"></script>
	<style>
		body
		{
			border-top:4px solid #222;
			background:#FFF url(./templates/admin/images/top.gif) top repeat-x;
		}
		
		#banner
		{
			margin:10px;
			padding:10px 10px 0 10px;
		}
#element{margin:0 auto;padding:10px;width:500px;height:460px;color:#222;font-weight:bold;}
#element h2{font-size:10pt;color:#222;padding:15px 0;height:50px;margin:0 0 10px 0;font-family:'Georgia', 'Times New Roman', Times, serif;border-bottom:1px solid #DDD;}
#element h2 img{margin-bottom:0px;margin-top:0px;float;left}
#element h2 span{float:right;margin-top:-10px}
#element #show{color:#222;text-align:left;font-size:9pt;font-weight:normal;}
#element .button{background:#EEE;border:1px solid #999;width:auto;color:#000;padding:5px;height:auto}
#element p{padding:10px 0}
#element #show p{padding:2px 0;}
	</style>
</head>
<body>

<?php
if(!isset($_GET["step"])) $_GET["step"] = "1";
switch($_GET["step"])
{
	case "1":
		install_step_one();
		break;
	case "2":
		install_step_two();
		break;
	case "finish":
		install_step_finish();
		break;
	default:
		break;
}

function install_step_one()
{
?>
<div id="element">
	<h2><img width="200" src="./templates/admin/images/logo.jpg" alt="logo" /><span>Magike安装程序第一步</span></h2>
<form method="post" action="<?php echo install_get_siteurl()."/install.php?step=2" ?>"/>
	<p>数据库主机: <input type="text" class="text" size=40 name="host" value="localhost" /></p>
	<p>数据库用户: <input type="text" class="text" size=40 name="user" /></p>
	<p>数据库密码: <input type="text" class="text" size=40 name="password" /></p>
	<p>数据库名称: <input type="text" class="text" size=40 name="dbname" /></p>
	<p>数据表前缀: <input type="text" class="text" size=40 name="one" value="mg_" /></p>
	<div id="show">
	<p>请将根目录权限修改为 777</p>
	<p>请填写Mysql数据库相关资料,我们将对您的数据库进行初始化</p>
	<p>如果您不知道以上资料,请跟您的主机提供商联系</p>
	<p><input type="submit" class="button" value="下一步&raquo;" /></p>
	</div>
</form>
</div>
<?php
}

function install_step_two()
{
$config = "<?php
/**********************************
 * Created on: 2006-12-2
 * File Name : config.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

define('__DBOBJECT__','magike_mysql');
define('__DBHOST__','{$_POST['host']}');
define('__DBUSER__','{$_POST['user']}');
define('__DBPASS__','{$_POST['password']}');
define('__DBNAME__','{$_POST['dbname']}');
define('__DBPREFIX__','{$_POST['one']}');
?>";

file_put_contents("./config.php",$config);

//创建rewrite
//获取相对路径
$basepath = str_replace(basename($_SERVER["PHP_SELF"]),"",$_SERVER["PHP_SELF"]);
$rewrite = "<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase {$basepath}
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule   ^(.*)$  index.php/$1 [L]
</IfModule>";
file_put_contents("./.htaccess",$rewrite);

//初始化数据库
$dblink=@mysql_connect($config['host'], $config['user'], $config['password']) or die("数据库连接错误,请检查您的设置");
@mysql_select_db($config['dbname'], $dblink) or die("对不起,您指定的数据库并不存在");

		mysql_query("SET NAMES 'utf8'");
		mysql_query("drop table if exists `{$config['one']}cats`");
		$sql = mysql_query("
		CREATE TABLE `{$config['one']}cats` (
  		`CA_ID` int(11) unsigned NOT NULL auto_increment,
  		`ca_name` varchar(100) NOT NULL default '',
  		`ca_postname` varchar(100) NOT NULL default '',
  		`ca_image` varchar(64) default 'default.gif',
  		`ca_describe` varchar(200) default NULL,
  		`ca_sort` int(10) unsigned default '0',
  		`ca_hide` int(1) unsigned NOT NULL default '0',
  		`ca_count` int(11) unsigned NOT NULL default '0',
  			PRIMARY KEY  (`CA_ID`)
		) TYPE=MyISAM CHARSET=utf8
		") or die(mysql_error());
		
		mysql_query("drop table if exists `{$config['one']}comments`");
		$sql = mysql_query("
		CREATE TABLE `{$config['one']}comments` (
  		`C_ID` int(11) unsigned NOT NULL auto_increment,
  		`co_user` varchar(64) NOT NULL default '',
  		`co_date` datetime NOT NULL default '0000-00-00 00:00:00',
  		`co_email` varchar(64) default NULL,
  		`co_homepage` varchar(64) default NULL,
  		`co_agent` varchar(200) default NULL,
  		`co_ip` varchar(64) NOT NULL default '0.0.0.0',
  		`co_text` text NOT NULL,
  		`co_title` varchar(100) default NULL,
  		`co_postID` int(10) unsigned NOT NULL default '0',
  		`co_type` enum('comment','ping') NOT NULL default 'comment',
  		`co_publish` enum('approved','spam','waitting') NOT NULL default 'approved',
 		 PRIMARY KEY  (`C_ID`)
		) TYPE=MyISAM CHARSET=utf8
		") or die(mysql_error());
		
		mysql_query("drop table if exists `{$config['one']}linkcats`");
		$sql = mysql_query("
		CREATE TABLE `{$config['one']}linkcats` (
		  `LC_ID` int(11) unsigned NOT NULL auto_increment,
		  `lc_name` varchar(100) NOT NULL default '',
		  `lc_describe` varchar(100) NOT NULL default '',
		  `lc_image` varchar(100) NOT NULL default '',
		  `lc_hide` int(1) NOT NULL default '0',
		  `lc_listnum` int(5) NOT NULL default '0',
		  `lc_sort` int(11) default '0',
		  `lc_linksort` enum('asc','desc','rand') NOT NULL default 'asc',
		  `lc_showlogo` enum('show','hide','has','only') NOT NULL default 'show',
		  PRIMARY KEY  (`LC_ID`)
			)TYPE=MyISAM CHARSET=utf8
		") or die(mysql_error());		
		
		mysql_query("drop table if exists `{$config['one']}links`");
		$sql = mysql_query("
		CREATE TABLE `{$config['one']}links` (
		  `L_ID` int(11) unsigned NOT NULL auto_increment,
		  `l_name` varchar(100) NOT NULL default '',
		  `l_describe` varchar(200) default NULL,
		  `l_url` varchar(100) NOT NULL default '',
		  `l_image` varchar(100) default NULL,
		  `l_cats` int(11) unsigned NOT NULL default '0',
		  `l_api` varchar(100) default NULL,
		  `l_hide` int(1) NOT NULL default '0',
		  PRIMARY KEY  (`L_ID`)
		) TYPE=MyISAM CHARSET=utf8
		") or die(mysql_error());
		
		mysql_query("drop table if exists `{$config['one']}posts`");
		$sql = mysql_query("
		CREATE TABLE `{$config['one']}posts` (
		  `ID` int(11) unsigned NOT NULL auto_increment,
		  `title` varchar(200) NOT NULL default '',
		  `post_name` varchar(200) default NULL,
		  `time` datetime NOT NULL default '0000-00-00 00:00:00',
		  `edit_time` datetime NOT NULL default '0000-00-00 00:00:00',
		  `password` varchar(20) NOT NULL default '',
		  `publish` enum('publish','draft','hidden') NOT NULL default 'publish',
		  `type` enum('archive','page','annouce','side') NOT NULL default 'archive',
		  `content` text NOT NULL,
		  `tags` varchar(200) default NULL,
		  `category` int(11) default '0',
		  `user` int(11) NOT NULL default '0',
		  `user_name` varchar(64) NOT NULL default '',
		  `access` varchar(20) NOT NULL default '',
		  `commentnum` int(10) unsigned NOT NULL default '0',
		  `weather` varchar(64) NOT NULL default 'sun',
		  PRIMARY KEY  (`ID`)
		) TYPE=MyISAM CHARSET=utf8
		") or die(mysql_error());
		
		mysql_query("drop table if exists `{$config['one']}tags`");
		$sql = mysql_query("
		CREATE TABLE `{$config['one']}tags` (
 		 `t_name` varchar(100) NOT NULL default '',
		  `t_count` int(5) NOT NULL default '0'
		) TYPE=MyISAM CHARSET=utf8
		") or die(mysql_error());
		
		mysql_query("drop table if exists `{$config['one']}userextend`");
		$sql = mysql_query("
		CREATE TABLE `{$config['one']}userextend` (
		  `UE_ID` int(11) unsigned NOT NULL auto_increment,
		  `ue_uid` int(11) unsigned NOT NULL default '0',
		  `ue_address` varchar(200) default NULL,
		  `ue_msn` varchar(100) default NULL,
		  `ue_qq` varchar(64) default NULL,
		  `ue_phone` varchar(64) default NULL,
		  `ue_sex` enum('male','female') NOT NULL default 'male',
		  `ue_zip` varchar(12) default NULL,
		  PRIMARY KEY  (`UE_ID`)
		) TYPE=MyISAM CHARSET=utf8
		") or die(mysql_error());
		
		mysql_query("drop table if exists `{$config['one']}users`");
		$sql = mysql_query("
		CREATE TABLE `{$config['one']}users` (
		  `U_ID` int(11) unsigned NOT NULL auto_increment,
		  `u_name` varchar(64) NOT NULL default '',
		  `u_password` varchar(64) NOT NULL default '',
		  `u_mail` varchar(64) NOT NULL default '',
		  `u_url` varchar(64) default NULL,
		  `u_nick` varchar(64) default NULL,
		  `u_level` varchar(10) NOT NULL default '',
		  `u_header` varchar(100) default NULL,
		  `u_about` text,
		  `u_lastvisit` datetime default '0000-00-00 00:00:00',
		  `u_lastip` varchar(64) default '0.0.0.0',
 		 PRIMARY KEY  (`U_ID`)
		) TYPE=MyISAM CHARSET=utf8
		") or die(mysql_error());
		  
		$sql = mysql_query("
		INSERT INTO `{$config['one']}cats` VALUES (1, '默认分类', 'default', 'default.gif', '这是一个默认分类', 1, 0, 1)
		") or die(mysql_error());
		
		$sql = mysql_query("
		INSERT INTO `{$config['one']}comments` VALUES (1, 'Magike', '2006-08-13 05:13:43', 'magike.net@gmail.com', 'http://www.magike.net', 'Magike\0.1b', '127.0.0.1', '欢迎使用Magike,这是一条测试评论', NULL, 1, 'comment', 'approved')
		") or die(mysql_error());
		
		$sql = mysql_query("
		INSERT INTO `{$config['one']}linkcats` VALUES (1, 'blogroll', '我的收藏', 'box.gif', 0, 10, 1, 'rand', 'has')
		") or die(mysql_error());
		
		$sql = mysql_query("
		INSERT INTO `{$config['one']}links` VALUES (1, 'Magike.Net', 'Magike官方主页', 'http://www.magike.net', 'http://www.magike.net/images/logo.gif', 1, '', 0)
		") or die(mysql_error());
		
		$sql = mysql_query("
		INSERT INTO `{$config['one']}posts` VALUES (1 , '欢迎使用Magike', 'welcome', '2006-06-12 00:00:00', '2006-06-28 00:00:00', '', 'publish', 'archive', '欢迎您使用Magike开始激动人心的博客之旅,这是一篇测试文章,您可以删除或者更改它.', '', 1, 1, 'KsQi', 'ping|comment', 1, 'sun')
		") or die(mysql_error());
		
		$sql = mysql_query("
		INSERT INTO `{$config['one']}posts` VALUES (2 , '关于我们', 'about', '2006-06-12 00:00:00', '2006-06-28 00:00:00', '', 'publish', 'page', '这是一个测试页面', '', 1, 1, 'KsQi', '', 1, 'sun')
		") or die(mysql_error());
		
		$sql = mysql_query("
		INSERT INTO `{$config['one']}userextend` VALUES (1, 1, '', '', '', '', 'male', '')
		") or die(mysql_error());
    	$sql = mysql_query("
    	INSERT INTO `{$config['one']}users` VALUES (1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'webmaster@magike.net', '".install_get_siteurl()."', '', 'admin', '', '', '0000-00-00 00:00:00', '')
    	") or die(mysql_error());
?>
<div id="element" style="height:300px">
	<h2>Magike安装程序第二步</h2>
<form method="post" action="<?php echo install_get_siteurl()."/install.php?step=finish" ?>"/>
	<p>站点地址: <input type="text" name="siteurl" value="<?php echo install_get_siteurl(); ?>" /></p>
	<p>站点名称: <input type="text" name="blogname" /></p>
	<p>站点描述: <input type="text" name="describe" /></p>
	<div id="show">
	<p>请填写站点相关资料,您以后可以修改它们</p>
	<p><input type="submit" class="button" value="下一步" /></p>
	</div>
</form>
</div>
<?php
}

function install_step_finish()
{
$option = "<?php die(); ?>
<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<magike-option>
	<option name=\"global\">
		<value name=\"charset\"><![CDATA[UTF-8]]></value>
		<value name=\"language\"><![CDATA[zh_cn_utf8]]></value>
		<value name=\"template\"><![CDATA[default]]></value>
		<value name=\"admin_template\"><![CDATA[admin]]></value>
		<value name=\"xml_template\"><![CDATA[xml]]></value>
		<value name=\"blogname\"><![CDATA[Magike.Net]]></value>
		<value name=\"describe\"><![CDATA[{$_POST['describe']}]]></value>
		<value name=\"siteurl\"><![CDATA[{$_POST['siteurl']}]]></value>
		<value name=\"index\"><![CDATA[{$_POST['siteurl']}/index.php]]></value>
		<value name=\"timezone\"><![CDATA[+8]]></value>
		<value name=\"site_closed\"><![CDATA[no]]></value>
		<value name=\"content-type\"><![CDATA[text/html]]></value>
		<value name=\"version\"><![CDATA[Magike 0.1b]]></value>
	</option>
	<option name=\"option\">
		<value name=\"postnum\"><![CDATA[5]]></value>
		<value name=\"article_date\"><![CDATA[M jS,Y]]></value>
		<value name=\"article_listnum\"><![CDATA[10]]></value>
		<value name=\"article_subnum\"><![CDATA[10]]></value>
		<value name=\"article_charnum\"><![CDATA[600]]></value>
		<value name=\"article_use_charnum\"><![CDATA[yes]]></value>
		<value name=\"article_editor_rows\"><![CDATA[30]]></value>
		<value name=\"article_publish_level\"><![CDATA[admin]]></value>
		<value name=\"comment_date\"><![CDATA[Y年m月d日 H时i分]]></value>
		<value name=\"comment_sort\"><![CDATA[ASC]]></value>
		<value name=\"comment_listnum\"><![CDATA[10]]></value>
		<value name=\"comment_subnum\"><![CDATA[10]]></value>
		<value name=\"trackback_subnum\"><![CDATA[100]]></value>
		<value name=\"comment_link_spam\"><![CDATA[1]]></value>
		<value name=\"comment_ban_word\"/>
		<value name=\"comment_use_lenth\"><![CDATA[yes]]></value>
		<value name=\"comment_lenth\"><![CDATA[1000]]></value>
		<value name=\"comment_allow_tag\"/>
		<value name=\"comment_confrm\"><![CDATA[no]]></value>
		<value name=\"comment_information_mail\"><![CDATA[yes]]></value>
		<value name=\"comment_information_url\"><![CDATA[no]]></value>
		<value name=\"comment_ban_ip\"/>
		<value name=\"comment_validatecode\"><![CDATA[no]]></value>
		<value name=\"user_login_validatecode\"><![CDATA[no]]></value>
		<value name=\"user_allow_register\"><![CDATA[no]]></value>
		<value name=\"user_location_admin\"><![CDATA[admin]]></value>
	</option>
	<option name=\"level\">
		<value name=\"admin\"><![CDATA[0]]></value>
		<value name=\"visitor\"><![CDATA[3]]></value>
	</option>
	<option name=\"ipconf\"><value name=\"3232235521-3232235775\"><![CDATA[192.168.0.1-192.168.0.255]]></value></option>
	<option name=\"admin\">
		<value name=\"global\"><![CDATA[value]]></value>
		<value name=\"level\"><![CDATA[value]]></value>
		<value name=\"ipconf\"><![CDATA[value]]></value>
		<value name=\"mod\"><![CDATA[value]]></value>
		<value name=\"write\"><![CDATA[edit]]></value>
		<value name=\"edit\"><![CDATA[edit]]></value>
		<value name=\"addcats\"><![CDATA[edit]]></value>
		<value name=\"category\"><![CDATA[edit]]></value>
		<value name=\"article_conf\"><![CDATA[edit]]></value>
		<value name=\"comment\"><![CDATA[comment]]></value>
		<value name=\"comment_conf\"><![CDATA[comment]]></value>
		<value name=\"linkcats\"><![CDATA[link]]></value>
		<value name=\"addlinkcats\"><![CDATA[link]]></value>
		<value name=\"link_manage\"><![CDATA[link]]></value>
		<value name=\"links\"><![CDATA[link]]></value>
		<value name=\"users\"><![CDATA[user]]></value>
		<value name=\"profile\"><![CDATA[user]]></value>
		<value name=\"adduser\"><![CDATA[user]]></value>
		<value name=\"register_conf\"><![CDATA[user]]></value>
		<value name=\"file_list\"><![CDATA[file]]></value>
		<value name=\"file_edit\"><![CDATA[file]]></value>
	</option>
</magike-option>";
build_cache("./data/option/option.php",$option);
chmod("./data/option/option.php",0777);

$path = "<?php die(); ?>
<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<magike-vpath>
	<path url=\"/\" access=\"visitor\" action=\"template\">index.tpl</path>
	<path url=\"/[url=%s]\" access=\"visitor\" action=\"template\">page.tpl</path>
	<path url=\"/rss\" access=\"visitor\" action=\"xml_template\">rss2.tpl</path>
	<path url=\"/rss/archives/[p=%d]\" access=\"visitor\" action=\"xml_template\">rss2.tpl</path>
	<path url=\"/rss/category/[cat=%s]\" access=\"visitor\" action=\"xml_template\">rss2.tpl</path>
	<path url=\"/rss/[type=%s]\" access=\"visitor\" action=\"xml_template\">rss2.tpl</path>
	<path url=\"/trackback/[p=%d]\" access=\"visitor\" action=\"xml_template\">trackback.tpl</path>
	<path url=\"/archives/[p=%d]\" access=\"visitor\" action=\"template\">post.tpl</path>
	<path url=\"/page/[page=%d]\" access=\"visitor\" action=\"template\">index.tpl</path>
	<path url=\"/tag/[tag=%s]\" access=\"visitor\" action=\"template\">archives.tpl</path>
	<path url=\"/category/[cat=%s]\" access=\"visitor\" action=\"template\">archives.tpl</path>
	<path url=\"/category/[cat=%s]/[page=%d]\" access=\"visitor\" action=\"template\">archives.tpl</path>
	<path url=\"/tag/[tag=%s]/[page=%d]\" access=\"visitor\" action=\"template\">archives.tpl</path>
	<path url=\"/error/[code=%s]\" access=\"visitor\" action=\"template\">error.tpl</path>
	<path url=\"/admin\" access=\"admin\" action=\"admin_template\">main.tpl</path>
	<path url=\"/admin/login\" access=\"visitor\" action=\"admin_template\">login.tpl</path>
	<path url=\"/admin/logout\" access=\"visitor\" action=\"function\">admin_logout</path>
	<path url=\"/admin/article_conf\" access=\"admin\" action=\"admin_template\">article_conf.tpl</path>
	<path url=\"/admin/panel\" access=\"admin\" action=\"admin_template\">panel.tpl</path>
	<path url=\"/admin/write\" access=\"admin\" action=\"admin_template\">write.tpl</path>
	<path url=\"/admin/edit\" access=\"admin\" action=\"admin_template\">edit.tpl</path>
	<path url=\"/admin/category\" access=\"admin\" action=\"admin_template\">category.tpl</path>
	<path url=\"/admin/addcats\" access=\"admin\" action=\"admin_template\">addcats.tpl</path>
	<path url=\"/admin/option/global\" access=\"admin\" action=\"admin_template\">option_global.tpl</path>
	<path url=\"/admin/option/level\" access=\"admin\" action=\"admin_template\">option_level.tpl</path>
	<path url=\"/admin/option/mod\" access=\"admin\" action=\"admin_template\">option_mod.tpl</path>
	<path url=\"/admin/option/ipconf\" access=\"admin\" action=\"admin_template\">option_ipconf.tpl</path>
	<path url=\"/admin/option/mod/[do=%s]\" access=\"admin\" action=\"admin_template\">option_mod.tpl</path>
	<path url=\"/admin/comment\" access=\"admin\" action=\"admin_template\">comment.tpl</path>
	<path url=\"/admin/linkcats\" access=\"admin\" action=\"admin_template\">linkcats.tpl</path>
	<path url=\"/admin/links\" access=\"admin\" action=\"admin_template\">links.tpl</path>
	<path url=\"/admin/adduser\" access=\"admin\" action=\"admin_template\">adduser.tpl</path>
	<path url=\"/admin/users\" access=\"admin\" action=\"admin_template\">users.tpl</path>
	<path url=\"/admin/file_list\" access=\"admin\" action=\"admin_template\">file_list.tpl</path>
	<path url=\"/admin/file_edit\" access=\"admin\" action=\"admin_template\">file_edit.tpl</path>
	<path url=\"/admin/register_conf\" access=\"admin\" action=\"admin_template\">register_conf.tpl</path>
	<path url=\"/admin/profile\" access=\"admin\" action=\"admin_template\">profile.tpl</path>
	<path url=\"/admin/link_manage\" access=\"admin\" action=\"admin_template\">link_manage.tpl</path>
	<path url=\"/admin/addlinkcats\" access=\"admin\" action=\"admin_template\">addlinkcats.tpl</path>
	<path url=\"/admin/comment_conf\" access=\"admin\" action=\"admin_template\">comment_conf.tpl</path>
	<path url=\"/admin/write/[p=%d]\" access=\"admin\" action=\"admin_template\">write.tpl</path>
	<path url=\"/admin/edit/[page=%d]\" access=\"admin\" action=\"admin_template\">edit.tpl</path>
	<path url=\"/admin/profile/[user=%d]\" access=\"admin\" action=\"admin_template\">profile.tpl</path>
	<path url=\"/admin/adduser/[user=%d]\" access=\"admin\" action=\"admin_template\">adduser.tpl</path>
	<path url=\"/admin/addcats/[cat=%d]\" access=\"admin\" action=\"admin_template\">addcats.tpl</path>
	<path url=\"/admin/addlinkcats/[cat=%d]\" access=\"admin\" action=\"admin_template\">addlinkcats.tpl</path>
	<path url=\"/admin/link_manage/[page=%d]\" access=\"admin\" action=\"admin_template\">link_manage.tpl</path>
	<path url=\"/admin/comment/[page=%d]\" access=\"admin\" action=\"admin_template\">comment.tpl</path>
	<path url=\"/admin/write/[p=%d]/[word=%s]\" access=\"admin\" action=\"admin_template\">write.tpl</path>
	<path url=\"/admin/links/[link=%d]\" access=\"admin\" action=\"admin_template\">links.tpl</path>
	<path url=\"/admin/upload_image/[y=%d]_[m=%d]_[d=%d]\" access=\"admin\" action=\"admin_template\">upload_image.tpl</path>
	<path url=\"/admin/upload_image/[y=%d]_[m=%d]_[d=%d]/[word=%s]\" access=\"admin\" action=\"admin_template\">upload_image.tpl</path>
	<path url=\"/validatecode.png\" access=\"visitor\" action=\"function\">validatecode</path>
	<path url=\"/xmlrpc\" access=\"visitor\" action=\"function\">xmlrpc</path>
</magike-vpath>";
build_cache("./data/option/path.php",$path);
chmod("./data/option/path.php",0777);
?>
<div id="element" style="height:300px">
	<h2>Magike安装完成!</h2>
	<p>您的程序已经成功安装完成了!</p>
	<p>后台管理员用户名默认为 admin ,密码为 123456</p>
	<p>强烈建议您登陆后立刻修改它们,根目录下的install.php还没有删除,请立刻删除</p>
	<p><a href="<?php echo install_get_siteurl()."/index.php/admin/login"; ?>" style="color:#FFF">点击这里登陆后台</a></p>
</div>
<?php
}
?>
<script>
	registerInputFocus("#element");
</script>
</body>
</html>
