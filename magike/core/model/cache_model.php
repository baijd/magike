<?php
/**********************************
 * Created on: 2006-11-30
 * File Name : cache_model.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class CacheModel extends MagikeObject
{
 	function __construct()
 	{
		parent::__construct();
 	}

 	public function checkCacheFile($file)
 	{
 		//根据监听函数检查对象文件
 		foreach($file as $key => $val)
 		{
			if(is_string($val['listener']))
			{
				$result = call_user_func(array($this,$val['listener']),$key);
			}
			else
			{
				$result = call_user_func($val['listener'],$key);
			}

			if(is_string($val['callback']))
			{

				if(!$result)
				{
					call_user_func(array($this,$val['callback']));
				}
			}
			else
			{
				if(!$result)
				{
					call_user_func($val['callback']);
				}
			}

			if(isset($val['else']))
			{
				if(is_string($val['else']))
				{
					if($result)
					{
						call_user_func(array($this,$val['else']));
					}
				}
				else
				{
					if($result)
					{
						call_user_func($val['else']);
					}
				}
			}
 		}
 	}

 	private function fileExists($file)
 	{
 		return file_exists($file);
 	}

 	private function dirExists($dir)
 	{
 		return is_dir($dir);
 	}

 	private function fileDate($file)
 	{
 		$files = array();

 		if(file_exists($file))
 		{
 			require($file);

 			if(!$files) return false;
 			foreach($files as $key => $val)
 			{
				if(!file_exists($key)) return false;
				if(@filemtime($key) != $val) return false;
 			}
 		}
 		else
 		{
 			return false;
 		}
 		return true;
 	}
}
?>
