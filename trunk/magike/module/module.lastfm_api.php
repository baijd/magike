<?php
/**********************************
 * Created on: 2007-8-14
 * File Name : module.lastfm_api.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class LastfmApi extends MagikeModule
{	
	public function runModule($args)
	{
		$require = array('id' => null,
					'time_format' => $this->stack['static_var']['post_date_format'],
					'sub' => 10);
		$getArgs = $this->initArgs($args,$require);
		$xmlDocument = new DOMDocument("1.0","UTF-8");
		$xmlDocument->loadXML(file_get_contents("http://ws.audioscrobbler.com/1.0/user/{$getArgs['id']}/recenttracks.xml"));
		$xpath = new DOMXPath($xmlDocument);
		$item = $xpath->query("//recenttracks/track");
		
		$result = array();
		foreach($item as $val)
		{
			$result[] = array(
			'artist' => $val->childNodes->item(1)->nodeValue,
			'name' => $getArgs['sub'] == 0 ? $val->childNodes->item(3)->nodeValue : mgSubStr($val->childNodes->item(3)->nodeValue,0,$getArgs['sub']),
			'mbid' => $val->childNodes->item(5)->nodeValue,
			'album' => $val->childNodes->item(7)->nodeValue,
			'url' => $val->childNodes->item(9)->nodeValue,
			'date' => date($getArgs['time_format'],$val->childNodes->item(11)->getAttribute("uts") + (28800 - $this->stack['static_var']['server_timezone'])),
			'timestamp' => $val->childNodes->item(11)->getAttribute("uts"),
			);
		}
		
		return $result;
	}
}
?>
