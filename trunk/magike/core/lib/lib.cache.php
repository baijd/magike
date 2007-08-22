<?php
/**********************************
 * Created on: 2007-2-2
 * File Name : lib.cache.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class Cache extends MagikeObject
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
				$result = $this->$val['listener']($key);
			}
			else
			{
				$result = $val['listener'][0]->$val['listener'][1]($key);
			}

			if(is_string($val['callback']))
			{

				if(!$result)
				{
					$this->$val['callback']();
				}
			}
			else
			{
				if(!$result)
				{
					$val['callback'][0]->$val['callback'][1]();
				}
			}

			if(isset($val['else']))
			{
				if(is_string($val['else']))
				{
					if($result)
					{
						$this->$val['else']();
					}
				}
				else
				{
					if($result)
					{
						$val['else'][0]->$val['else'][1]();
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
