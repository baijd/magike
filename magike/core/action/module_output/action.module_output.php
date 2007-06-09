<?php
/**********************************
 * Created on: 2007-2-12
 * File Name : action.module_output.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

define('E_ACTION_MODULEOUTPUT_FILENOTEXISTS','Module File Is Not Exists');
class ModuleOutput extends MagikeObject
{
	private $fileName;
	private $objName;
	
	function __construct($fileName)
	{
		parent::__construct();
		$this->fileName = __MODULE__.'/module.'.$fileName.'.php';
		$this->objName = $fileName;
		if(!file_exists($this->fileName))
		{
			$this->throwException(E_ACTION_MODULEOUTPUT_FILENOTEXISTS,$this->fileName);
		}
	}
	
	public function runAction()
	{
		$args = isset($_POST['args']) && is_array($_POST['args']) ? $_POST['args'] : array();
		
		require($this->fileName);
		$tmp = null;
		eval('$tmp = new '.mgFileNameToClassName($this->objName).'();');
		$output = call_user_func(array($tmp,'runModule'),$args);
		$this->stack['action']['content_type'] = "content-Type: {$this->stack['static_var']['content_type']}; charset={$this->stack['static_var']['charset']}";
		header($this->stack['action']['content_type']);
		echo $output;
	}
}
?>
