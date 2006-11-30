<?php
/**********************************
 * Created on: 2006-11-30
 * File Name : template.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class Template extends module
{
	function __construct($args)
	{

	}

	function checkArgs($args)
	{
		//定义所需参数列表以共检验
		$requireArgs = array('template_file','template_path','compile_path','language_path');

		//校验参数是否定义
		$defined = array_diff($requireArgs,array_keys($args)) == NULL ? true : false;

		if($defined)
		{
			if(!is_dir($args['template_path']))
			{

			}
		}
	}
}
?>
