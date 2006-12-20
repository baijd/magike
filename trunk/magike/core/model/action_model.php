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
				$this->stack->setStack('static','content_type','text/xml');
				$this->runModule();
				$this->template->prase();
				break;
			case 'admin_template':
				$this->initTemplateObject($this->stack->data['system']['file'],$this->stack->data['static']['admin_template']);
				$this->runModule();
				$this->template->prase();
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
		$moduleObjects = array();
		$moduleOrder = array();

		if(isset($this->stack->data['module_to_run']))
		{
			foreach($this->stack->data['module_to_run'] as $val)
			{
				if(class_exists($val))
				{
					$moduleObject = $val;
					$moduleObject[0] = strtolower($moduleObject[0]);
					$moduleObjects[] = MagikeAPI::modelToFile($val);

					eval("\${$moduleObject} = new $val();");
				}
			}

			//生成模块运行顺序
			if(isset($this->stack->data['system']['template']) && $this->stack->data['module_to_run'])
			{
				if(!file_exists(__COMPILE__.'/'.$this->stack->data['system']['template'].'.rtm.php'))
				{

					while($moduleObjects)
					{
						$module = array_shift($moduleObjects);
						$moduleObject = MagikeAPI::fileToModule($module,false);
						eval("\$moduleObject = \${$moduleObject};");
						if(property_exists($moduleObject,'waitingModule'))
						{
							if(is_string($moduleObject->waitingModule))
							{
								if(in_array($moduleObject->waitingModule,$this->moduleObjects))
								{
									if(in_array($moduleObject->waitingModule,$this->moduleOrder))
									{
										$moduleOrder[] = $module;
									}
									else
									{
										array_push($moduleObjects,$module);
									}
								}
							}
							else if(is_array($moduleObject->waitingModule))
							{
								if(NULL == array_diff($moduleObject->waitingModule,$this->moduleObjects))
								{
									if(NULL == array_diff($moduleObject->waitingModule,$this->moduleOrder))
									{
										$moduleOrder[] = $module;
									}
									else
									{
										array_push($moduleObjects,$module);
									}
								}
							}
						}
						else
						{
							$moduleOrder[] = $module;
						}
					}

					MagikeAPI::exportArrayToFile(__COMPILE__.'/'.$this->stack->data['system']['template'].'.rtm.php',$moduleOrder,'moduleOrder');
				}
				else
				{
					require(__COMPILE__.'/'.$this->stack->data['system']['template'].'.rtm.php');
				}
			}

			//按顺序运行模块
			foreach($moduleOrder as $val)
			{
				$moduleObject = MagikeAPI::fileToModule($val,false);
				eval("\$moduleObject = \${$moduleObject};");
				if(method_exists($moduleObject,'runModule'))
				{
					call_user_func(array($moduleObject,'runModule'));
				}
				else
				{
					trigger_error('Method runModule Not Exists: '.$moduleObject,E_USER_NOTICE);
				}
			}
		}

		if(isset($this->stack->data['require_language']) && $this->stack->data['require_language'] != NULL)
		{
			if(isset($this->stack->data['system']['template']))
			{
				MagikeAPI::exportArrayToFile(__COMPILE__.'/'.$this->stack->data['system']['template'].'.lng.php',$this->stack->data['require_language'],'lang');
			}
		}
	}
}
?>
