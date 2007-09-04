<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.setting_permalink.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class SettingPermalink extends MagikeModule
{
	public function runModule()
	{
		$template = isset($_GET['template']) ? $_GET['template'] : $this->stack['static_var']['template'];
		$dir = __MODULE__.'/permalink/';
		$result = array();
		$files = mgGetFile($dir,true,'map');

		foreach($files as $val)
		{
			$lines = file($dir.$val);
			
			$word = array();
			foreach($lines as $inval)
			{
				$str = trim($inval);
				if(NULL == $str)
				{
					continue;
				}
				else
				{
					if("#" == $str[0])
					{
						$word[] = substr($str,1,strlen($str) - 1);
					}
					else
					{
						continue;
					}
				}
			}
			
			$item['word'] = implode('<br />',$word);
			$item['key'] = $val;
			
			$result[] = $item;
		}
		
		return $result;
	}
}
?>
