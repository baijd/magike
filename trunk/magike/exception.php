<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="{$static_var.content_type};charset=UTF-8" />
	<title><?php echo $this->message;?></title>
	<style>
		body
		{
			border-top:4px solid #383D44;
			padding:0;
			margin:0;
			background:#FFF;
		}
		
		#exception
		{
			background:#383D44;
			margin:0 auto;
			width:300px;
			height:400px;
			border:1px solid #CCC;
			border-top:none;
			padding:20px;
		}
	</style>
</head>
<body>
	<div id="exception">
		<h2><?php echo $this->message;?></h2>
	</div>
</body>
</html>