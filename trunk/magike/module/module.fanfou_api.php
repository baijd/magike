<?php
/**********************************
 * Created on: 2007-8-14
 * File Name : module.fanfou_api.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class FanfouApi extends MagikeModule
{	
	public function runModule($args)
	{
		$require = array('count' => 20,
					'id' => null,
					'time_format' => $this->stack['static_var']['post_date_format'],
					'sub' => 10);
		$getArgs = $this->initArgs($args,$require);
		$item = Json::json_decode(file_get_contents("http://api.fanfou.com/statuses/user_timeline.json?id={$getArgs['id']}&count={$getArgs['count']}"));
		$result = array();
		foreach($item as $val)
		{
			$result [] = array(
			'screen_name' => $val->user->screen_name,
			'id' => $val->user->id,
			'name' => $val->user->name,
			'location' => $val->user->location,
			'description' => $val->user->description,
			'profile_image_url' => $val->user->profile_image_url,
			'url' => $val->user->url,
			'protected' => $val->user->protected,
			'created_at' => date($getArgs['time_format'],strtotime($val->created_at) + (28800 - $this->stack['static_var']['server_timezone'])),
			'status_id' => $val->id,
			'text' => $getArgs['sub'] == 0 ? $val->text : mgSubStr($val->text,0,$getArgs['sub'])
			);
		}

		return $result;
	}
}
?>
