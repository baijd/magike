<?php
/**********************************
 * Created on: 2006-11-30
 * File Name : magike.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class Magike extends MagikeObject
{
	function __construct()
	{
		$this->initPublicObject(array('stack'));
		$this->initPrivateObject(array('cache'));
		$this->cache->checkCacheFile(array(__CACHE__.'/system/module.php' => array('listener' => 'fileExists',
																	 'callback' => array($this,'buildCache'),
																	 'else'	=> array($this,'loadCache')
																	 )));

		$this->initPublicObject(array('static','path','access','action'));
		print_r($this->stack->data);
	}

	function buildCache()
	{
		$moduleFile = MagikeAPI::getFile(__MODULE__,false,'php');
		$module = array();

		foreach($moduleFile as $fileName)
		{
			require(__MODULE__.'/'.$fileName.'.php');
			$currentModule = MagikeAPI::fileToModule($fileName);

			if(class_exists($currentModule))
			{
				$module[$fileName] = __MODULE__.'/'.$fileName.'.php';
			}
		}

		$this->stack->setStackByType('module',$module);
		MagikeAPI::exportArrayToFile(__CACHE__.'/system/module.php',$module,'module');
	}

	function loadCache()
	{
		$module = array();
		require(__CACHE__.'/system/module.php');
		$this->stack->setStackByType('module',$module);
	}
}
?>
