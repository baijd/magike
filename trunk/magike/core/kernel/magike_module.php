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

	protected function onPost($key,$callback,$val = NULL)
	{
		if($val)
		{
			if(isset($_POST[$key]) && $_POST[$key] == $val)
			{
				call_user_func(array($this,$callback));
			}
		}
		else
		{
			if(isset($_POST[$key]))
			{
				call_user_func(array($this,$callback));
			}
		}
	}

	protected function onGet($key,$callback,$val = NULL)
	{
		if($val)
		{
			if(isset($_GET[$key]) && $_GET[$key] == $val)
			{
				call_user_func(array($this,$callback));
			}
		}
		else
		{
			if(isset($_GET[$key]))
			{
				call_user_func(array($this,$callback));
			}
		}
	}

	protected function getLanguage($moduleName,$key)
	{
		if(!isset($this->stack->data['language'][$moduleName]))
		{
			if(file_exists(__LANGUAGE__.'/'.$this->stack->data['static']['language'].'/'.$moduleName.'.php'))
			{
				$lang = array();
				require(__LANGUAGE__.'/'.$this->stack->data['static']['language'].'/'.$moduleName.'.php');
				$this->stack->setStack('language',$moduleName,$lang);
			}
			$this->stack->data['require_language'][$moduleName][$key] =
			isset($this->stack->data['language'][$moduleName][$key]) ? $this->stack->data['language'][$moduleName][$key] : NULL;
		}
		return isset($this->stack->data['language'][$moduleName][$key]) ? $this->stack->data['language'][$moduleName][$key] : NULL;
	}
}
?>
