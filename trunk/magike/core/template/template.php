<?php
/**********************************
 * Created on: 2006-11-30
 * File Name : template.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class Template extends MagikeObject
{
	private $templateFile;
	private $template;
	private $templateName;
	private $block;
	private $data;
	private $blank;

	function __construct($templateFile,$template)
	{
		parent::__construct(array('public' => array('stack'),
								  'private'=> array('cache')));
		$this->templateFile = $templateFile;
		$this->template = $template;
		$this->templateName = str_replace('.tpl','',$template);
		$this->block = array();
		$this->data = array();

		$this->cache->checkCacheFile(
		array(__COMPILE__.'/'.$this->templateName.'@'.$this->template.'@'
		.$this->stack->data['static']['language'].'.cnf.php' => array('listener' => 'fileDate',
																	  'callback' => array($this,'buildCache'),
																	  'else'	=> array($this,'loadCache'))));

		$this->data['static'] = array_merge($this->data['static'],$this->stack->data['static']);
		$this->data['system'] = array_merge($this->data['system'],$this->stack->data['system']);
	}

	private function buildCache()
	{
		require(__DIR__.'/template/build.php');
		new Build($this->templateFile,$this->template);
	}

	private function loadCache()
	{
		$module = array();
		$data = array();
		$block = array();

		require(__COMPILE__.'/'.$this->templateName.'@'.$this->template.'@'.$this->stack->data['static']['language'].'.php');
		$this->stack->setStackByType('module',$module);
		$this->data = $data;
		$this->blank = $data;
		$this->block = $block;

		if(!isset($this->data['static']))
		{
			$this->data['static'] = array();
		}
		if(!isset($this->data['system']))
		{
			$this->data['system'] = array();
		}
	}
}
?>
