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
		#element h2 span{float:right;margin-top:-12px}
		#element #show{color:#222;text-align:left;font-size:9pt;font-weight:normal;}
		#element .button{background:#EEE;border:1px solid #999;width:auto;color:#000;padding:2px 5px;height:auto}
		#element p{padding:10px 0}
		#element #show p{padding:5px 0;}
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
$isWriteAble = is_writeable('.');
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
	<?php if(!$isWriteAble){ ?><p style="color:red;font-weight:normal">请将根目录权限修改为 777</p><?php } ?>
	<p>请填写Mysql数据库相关资料,我们将对您的数据库进行初始化</p>
	<p>如果您不知道以上资料,请跟您的主机提供商联系</p>
	<p><input type="submit" class="button" value="下一步&raquo;" <?php if(!$isWriteAble){ ?>disabled=disable<?php } ?> /></p>
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
$dblink=@mysql_connect($_POST['host'], $_POST['user'], $_POST['password']) or die("数据库连接错误,请检查您的设置");
@mysql_select_db($_POST['dbname'], $dblink) or die("对不起,您指定的数据库并不存在");
?>
<div id="element" style="height:300px">
	<h2><img width="200" src="./templates/admin/images/logo.jpg" alt="logo" /><span>Magike安装程序第二步</span></h2>
<form method="post" action="<?php echo install_get_siteurl()."/install.php?step=finish" ?>"/>
	<p>站点地址: <input type="text"  class="text" size=40 name="siteurl" value="<?php echo install_get_siteurl(); ?>" /></p>
	<p>站点名称: <input type="text"  class="text" size=40 name="blogname" /></p>
	<p>站点描述: <input type="text"  class="text" size=40 name="describe" /></p>
	<p>电子邮件: <input type="text"  class="text" size=40 name="email" /></p>
	<div id="show">
	<p>您的数据库连接正常,现在可以开始初始化数据</p>
	<p><input type="submit" class="button" value="下一步" /></p>
	</div>
</form>
</div>
<?php
}

function install_step_finish()
{
require('./config.php');
$dblink=@mysql_connect(__DBHOST__, __DBUSER__,__DBPASS__) or die("数据库连接错误,请检查您的设置");
@mysql_select_db(__DBNAME__, $dblink) or die("对不起,您指定的数据库并不存在");
require('./query.php');
unlink('./query.php');
file_put_contents('./index.php',"<?php
/**********************************
 * Created on: 2006-12-3
 * File Name : index.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

include('./core/core.php');
?>
");
?>
<div id="element" style="height:300px">
	<h2><img width="200" src="./templates/admin/images/logo.jpg" alt="logo" /><span>Magike安装完成!</span></h2>
	<p>您的程序已经成功安装完成了!<span style="color:red">请立刻删除根目录下面的install.php文件</span></p>
	<p>后台管理员用户名默认为 admin ,密码为 12345</p>
	<p><a href="<?php echo install_get_siteurl()."/index.php/admin/login"; ?>" style="color:red">点击这里登陆后台</a></p>
</div>
<?php
}
?>
<script>
	registerInputFocus("#element");
</script>
</body>
</html>
