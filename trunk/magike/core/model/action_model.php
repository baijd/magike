<?php
/**********************************
 * Created on: 2006-12-6
 * File Name : action_model.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class ActionModel extends MagikeObject
{
	function __construct()
	{
		parent::__construct(array('public' => array('stack')));
		$this->analysisAction();
	}

	private function analysisAction()
	{
		switch($this->stack->data['system']['action'])
		{
			case 'template':
				$this->initTemplateObject($this->stack->data['system']['file'],$this->stack->data['static']['template']);
				$this->runModule();
				$this->template->prase();
				break;
			case 'xml_template':
				$this->initTemplateObject($this->stack->data['system']['file'],$this->stack->data['static']['xml_template']);
				break;
			case 'admin_template':
				$this->initTemplateObject($this->stack->data['system']['file'],$this->stack->data['static']['admin_template']);
				break;
			default:
				break;
		}
	}

	private function initTemplateObject($templateFile,$templatePath)
	{
		global $template;
		require(__DIR__.'/template/template.php');
		$template = new Template($templateFile,$templatePath);
		$this->template = $template;
	}

	private function runModule()
	{
		if(isset($this->stack->data['module_to_run']))
		{
			foreach($this->stack->data['module_to_run'] as $val)
			{
				if(class_exists($val))
				{
					$module = NULL;
					eval("\$module = new $val();");
					if(method_exists($module,'runModule'))
					{
						call_user_func(array($module,'runModule'));
					}
					else
					{
						trigger_error('Class Not Exists: '.$val,E_USER_NOTICE);
					}
				}
			}
		}
	}
}
?>
