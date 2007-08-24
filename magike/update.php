<?php
header("content-Type: text/html; charset=UTF-8");
function query($str)
{
	mysql_query($str) or die(mysql_error());
}

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

function mgRmDir($inpath)
{
	str_replace("//","/",$inpath);
	$dir = explode("/",$inpath);
	
	foreach($dir as $key => $val)
	{
		$path = implode("/",$dir);
		if(false == mgUnLink($path)) 
		{
			return false;
		}
		if(NULL != ($dirs = mgGetDir($path)))
		{
			foreach($dirs as $inkey => $inval)
			{
				if(mgRmDir($path."/".$inval) == false) return false;
			}
		}
		if(false == @rmdir($path))
		{
			return false;
		}
		if($inpath != $path)
		{
			array_pop($dir);
		}
		else break;
	}
	
	return true;
}

function mgUnLink($inpath)
{
	str_replace("//","/",$inpath);
	$files = mgGetFile($inpath,true);
	
	if(NULL != $files)
	{
		foreach($files as $key => $val)
		{
		if(@unlink($inpath."/".$val) == false) return false;
		}
	}
	
	return true;
}

//获取一个目录下的文件
function mgGetFile($inpath,$trim = false,$stamp = NULL)
{
	$file = array();

	if(!is_dir($inpath))
	{
		return $file;
	}

	$handle=opendir($inpath);
	if(NULL != $stamp)
	{
		$stamp = explode("|",$stamp);
	}

	while ($tmp = readdir($handle)) 
	{
		if(is_file($inpath."/".$tmp) && eregi("^([_@0-9a-zA-Z\x80-\xff\^\.\%-]{0,})[\.]([0-9a-zA-Z]{1,})$",$tmp,$file_name))
		{
			if($stamp != NULL && in_array($file_name[2],$stamp))
			{
				$file[] = $trim ? $file_name[0] : $file_name[1];
			}
			else if($stamp == NULL)
			{
				$file[] = $trim ? $file_name[0] : $file_name[1];
			}
		}
	}
	closedir($handle);
	return $file;
}

//获取一个目录下的目录
function mgGetDir($inpath)
{
	$handle=opendir($inpath);
	$dir = array();
	while ($tmp = readdir($handle))
	{
		if(is_dir($inpath."/".$tmp) && $tmp != ".." && $tmp != "." && 0 !== stripos($tmp,'.')) 
		{
			$dir[] = $tmp;
		}
	}
	closedir($handle);
	return $dir;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<title>Magike 1.0.1 升级程序</title>
	<style>
		body
		{
			border-top:4px solid #222;
			background:#FFF;
			padding:0;
			margin:0;
			font-family:verdana, Helvetica, sans-serif;
		}
		
		#banner
		{
			margin:10px;
			padding:10px 10px 0 10px;
		}
		#element{margin:0 auto;padding:10px;width:500px;height:460px;color:#222;font-weight:bold;line-height:24px}
		#element h1{font-size:16pt;font-weight:normal;text-align:center;}
		#element h2{font-size:10pt;color:#495A67;padding:0;margin:0;font-family:'Georgia', 'Times New Roman', Times, serif;border-bottom:1px solid #DDD;}
		#element h2 span{float:right;margin-top:-12px}
		#element ul{margin:10px 0 0 0;padding:0}
		#element ul li{margin:0 0 20px 0;list-style:none}
		#element #show{color:#222;text-align:left;font-size:9pt;font-weight:normal;}
		#element input{font-family:verdana, Helvetica, sans-serif;}
		#element input.text{padding:5px;width:200px;border:1px solid #DDD;}
		#element p{padding:10px 0 0 0;margin:10px 0 0 0;font-size:9pt;font-weight:normal;}
		#element span{margin-right:5px;font-size:20px;}
		#element #show p{padding:5px 0;}
	</style>
</head>
<body>
<?php
if(isset($_GET['step']) && $_GET['step'] == 'finish')
{
require('./config.php');
$dblink=@mysql_connect(__DBHOST__, __DBUSER__,__DBPASS__) or die("数据库连接错误,请检查您的设置");
@mysql_select_db(__DBNAME__, $dblink) or die("对不起,您指定的数据库并不存在");

query("SET NAMES 'utf8'");

query("REPLACE INTO `".__DBPREFIX__."paths` (`id`, `path_name`, `path_action`, `path_file`, `path_cache`,`path_describe`) VALUES 
(64, '/thumb/[file_id=%d]/[file_name=%p]', 'module_output', 'thumbnail_output', 0,'缩略图输出'),
(65, '/admin/posts/tags_search/', 'json_output', 'tags_search', 0,'后台标签搜索接口')");

query("REPLACE INTO `".__DBPREFIX__."path_group_mapping` (`id`, `path_id`, `group_id`) VALUES 
(81, 64, 1),
(82, 64, 2),
(83, 65, 1)");

query("REPLACE INTO `".__DBPREFIX__."statics` (`id`, `static_name`, `static_value`) VALUES 
(5, 'version', 'Magike 1.0.1'),
(43, 'write_editor_custom_tags', ''),
(44, 'build_version', '290')");

mgRmDir('./data/cache');
mgRmDir('./data/runtime');
mgRmDir('./data/compile');
?>
<div id="element">
	<h1>已经成功升级</h1>
	<ul>
	<li><h2>您的升级过程已经成功完成</h2>
	<p><a href="<?php echo install_get_siteurl(); ?>">点击访问您的网站.</a></p>
	</li>
	</ul>
</div>
<?php
}else{
?>
<div id="element">
	<h1>欢迎您使用Magike博客升级程序</h1>
	<p>此升级档仅适用于从1.0升级至1.0.1</p>
	<ul>
		<li><h2>开始升级</h2>
		<p><a href="?step=finish">点击这里开始简单的升级过程.</a></p>
		<p><strong>请您在完成升级后删除此文件!</strong></p>
		</li>
	</ul>
</div>
<?php
}
?>
</body>
</html>