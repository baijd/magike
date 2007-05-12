<?php
/**********************************
 * Created on: 2007-3-4
 * File Name : module.skins_list.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class SkinsList extends MagikeModule
{
	public function runModule()
	{
		$skinDirs = mgGetDir(__TEMPLATE__);
		$skins = array();
		foreach($skinDirs as $val)
		{
			if(file_exists(__TEMPLATE__.'/'.$val.'/screen.jpg'))
			{
				$item = array();
				if(file_exists(__TEMPLATE__.'/'.$val.'/readme.txt'))
				{
					$item['readme'] = file_get_contents(__TEMPLATE__.'/'.$val.'/readme.txt');
				}
				else
				{
					$item['readme'] = '';
				}
				
				$item['template'] = $val;
				$skins[] = $item;
			}
		}
		return $skins;
	}
}
?>
