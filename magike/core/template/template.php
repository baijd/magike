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
	public  $data;

	function __construct($templateFile,$template)
	{
		parent::__construct(array('public' => array('stack'),
								  'private'=> array('cache')));
		$this->templateFile = $templateFile;
		$this->template = $template;
		$this->templateName = str_replace('.tpl','',$templateFile);
		$this->stack->setStack('system','template',$this->templateName.'@'.$this->template);
		$this->data = array();

		$this->cache->checkCacheFile(
		array(__COMPILE__.'/'.$this->templateName.'@'.$this->template.'.cnf.php' => array('listener' => 'fileDate',
																	  'callback' => array($this,'buildCache'))));
		$module = array();
		require(__COMPILE__.'/'.$this->templateName.'@'.$this->template.'.inc.php');
		$this->stack->setStackByType('module_to_run',$module);
	}

	public function buildCache()
	{
		require(__DIR__.'/template/build.php');
		new Build($this->templateFile,$this->template);
	}

	public function prase($contenType = 'text/html')
	{
		ob_start();
		header("content-Type: {$this->stack->data['static']['content_type']}; charset={$this->stack->data['static']['charset']}");
		require(__COMPILE__.'/'.$this->templateName.'@'.$this->template.'.php');
		$contents = ob_get_contents();
		ob_end_clean();
		echo $contents;
	}
}
?>
