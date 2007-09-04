<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.page_navigator.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class PageNavigator extends MagikeModule
{
	protected function makeClassicNavigator($limit,$total,$query = NULL,$page = 'page')
	{		
		$page = isset($_GET[$page]) ? $_GET[$page] : 1;
		$result['next'] = $total > $page*$limit ? $page + 1 : 0;
		$result['prev'] = $page > 1 ? $page - 1 : 0;
		$result['total'] = $total%$limit > 0 ? intval($total/$limit) + 1
		: intval($total/$limit);
		$result['query'] = $query;
		
		$result['next_permalink'] = $this->stack['static_var']['index']."/".$query.$result['next'];
		$result['prev_permalink'] = $this->stack['static_var']['index']."/".$query.$result['prev'];
		
		return $result;
	}
}
?>
