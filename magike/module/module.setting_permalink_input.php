<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.setting_permalink_input.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class SettingPermalinkInput extends MagikeModule
{
	private $result;
	
	private function updatePath($pathFile,$pathModel)
	{
		$lines = file($pathFile);
		
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
					continue;
				}
				else
				{
					$str = str_replace("  "," ",$str);
					$item = explode(" ",$str);
					
					$pathMeta = trim($item[0]);
					$pathName = trim($item[1]);
					$pathCahe = isset($item[2]) && (null !== $item[2]) ? trim($item[2]) : 0;
					
					$args = array('path_name' => $pathName,
					  'path_cache' => $pathCahe);
					  
					$pathModel->updateByFiledEqual('path_meta',$pathMeta,$args);
				}
			}
		}
	}
	
	public function updatePermalink()
	{
		$this->requirePost();
		$staticModel = $this->loadModel('statics');
		
		$this->stack['static_var']['permalink_style'] = $_POST['permalink'];
		$staticModel->updateByFiledEqual('static_name','permalink_style',array('static_value' => $_POST['permalink']));
		
		$pathModel = $this->loadModel('paths');
		
		//首先恢复default
		$this->updatePath(__MODULE__.'/permalink/permalink.default.map',$pathModel);
		//然后开始更新
		$this->updatePath(__MODULE__.'/permalink/'.$_POST['permalink'],$pathModel);
		
		$this->deleteCache('static_var');
		$this->deleteCache('action');
		$this->deleteCache('permalink');
		$this->result['open'] = true;
		$this->result['word'] = '您的静态链接已经更新成功';
	}
	
	public function runModule()
	{
		$this->onPost("do","updatePermalink","update");
		return $this->result;
	}
}
?>
