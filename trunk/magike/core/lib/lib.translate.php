<?php
/**********************************
 * Created on: 2007-2-2
 * File Name : lib.translate.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class Translate extends MagikeModule
{
	static final public function prase($from,$to,$str)
	{
		$langFile = __LIB__."/translate/translate.{$from}_to_{$to}.php";
		if(file_exists($langFile))
		{
			require($langFile);
			$className = mgFileNameToClassName("{$from}_to_{$to}");
			$lang = new $className();
			return $lang->prase($str);
		}
		else
		{
			return $str;
		}
	}
}

?>