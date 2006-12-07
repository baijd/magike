<?php
/**********************************
 * Created on: 2006-12-6
 * File Name : magike_module.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class MagikeModule extends MagikeObject
{
	function __construct($require = array('database','template','stack'))
	{
		$modelMap = array('cache' 	=> 'private',
						  'database'=> 'public',
						  'stack'	=> 'public'
						  );

		$public = array();
		$private = array();

		//载入模型
		foreach($require as $val)
		{
			if(isset($modelMap[$val]))
			{
				if('public' == $modelMap[$val])
				{
					$public[] = $val;
				}
				else
				{
					$private[] = $val;
				}
			}
		}
		$this->initPublicObject($public);
		$this->initPrivateObject($private);


		if(in_array('template',$require))
		{
			global $template;
			$this->template = $template;
		}
	}
}
?>
