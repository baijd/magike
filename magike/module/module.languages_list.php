<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.languages_list.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class LanguagesList extends MagikeModule
{
	public function runModule()
	{
		return mgGetDir(__LANGUAGE__);
	}
}
?>
