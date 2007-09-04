<?php
/**********************************
 * Created on: 2007-2-12
 * File Name : kernel.permalink.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class Permalink extends MagikeModule
{
	private $pathCache;

	function __construct()
	{
		parent::__construct();
		$cache = new Cache();
		$cache->checkCacheFile(array($this->cacheFile  => array('listener' => 'fileExists',
									     'callback' => array($this,'buildCache')
									    )));
	}
	
	private function prasePathValue($path)
	{
		$value = array();

		if(preg_match_all("/\[([_0-9a-zA-Z-\\\]+)\=\%([d|s|p|a|])\]/i",$path,$out))
		{
			if(isset($out[1]) && $out[1])
			{
				foreach($out[1] as $key => $val)
				{
					$value[str_replace("\\","",$val)] = 0;
					$path = str_replace($out[0][$key],"%s",$path);
				}
			}
		}

		return array("path" => $path , "value" => $value);
	}
	
	public function buildCache()
	{
		$this->pathCache = array();
		$pathModel = new Database();
		$pathModel->fetch(array('table' => 'table.paths'),array('function' => array($this,'pushPathData')));
		mgExportArrayToFile($this->cacheFile,$this->pathCache,'permalink');
	}

	public function pushPathData($val)
	{
		if(false === strpos($val['path_name'],'/admin/') && 'exception' != $val['path_meta'] && 'index' != $val['path_meta'])
		{
			$this->pathCache[$val['path_meta']] = $this->prasePathValue($val['path_name']);
		}
	}
	
	public function runModule()
	{
		$permalink = array();
		require($this->cacheFile);
		
		//初始化一些静态链接
		$this->stack['static_var']['template_url'] = $this->stack['static_var']['siteurl'].'/'.__TEMPLATE__.'/'.$this->stack['static_var']['template'];
		$this->stack['static_var']['rss_permalink'] = $this->stack['static_var']['index'].$permalink['rss']['path'];
		$this->stack['static_var']['xmlrpc_url'] = $this->stack['static_var']['index'].$permalink['xmlrpc']['path'];
		$this->stack['static_var']['validator_url'] = $this->stack['static_var']['index'].$permalink['validator']['path'];
		$this->stack['static_var']['search_url'] = $this->stack['static_var']['index'].$permalink['search_archives']['path'];
		$this->stack['static_var']['register_url'] = $this->stack['static_var']['index'].$permalink['register']['path'];
		$this->stack['static_var']['tags_url'] = $this->stack['static_var']['index'].$permalink['tags']['path'];
		
		return $permalink;
	}
}
?>
