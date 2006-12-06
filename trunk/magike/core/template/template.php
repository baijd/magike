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
		$this->templateName = str_replace('.tpl','',$templateFile);
		$this->block = array();
		$this->data = array();

		$this->cache->checkCacheFile(
		array(__COMPILE__.'/'.$this->templateName.'@'.$this->template.'.cnf.php' => array('listener' => 'fileDate',
																	  'callback' => array($this,'buildCache'))));

		$this->loadCache();
		$this->data['static'] = MagikeAPI::array_intersect_key($this->stack->data['static'],$this->data['static']);
		$this->data['system'] = MagikeAPI::array_intersect_key($this->stack->data['system'],$this->data['system']);
	}

	public function buildCache()
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
		$this->stack->setStackByType('module_to_run',$module);
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
