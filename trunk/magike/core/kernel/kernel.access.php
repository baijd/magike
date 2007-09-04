<?php
/**********************************
 * Created on: 2007-2-12
 * File Name : kernel.access.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class Access extends MagikeModule
{
	public function runModule()
	{
		$result['login'] = (isset($_COOKIE['auth_data']) && isset($_SESSION['auth_data']) 
								 && ($_COOKIE['auth_data'] == $_SESSION['auth_data'])) ? true : false;
		$result['user_name'] = isset($_SESSION['user_name']) && $result['login'] ? $_SESSION['user_name'] : NULL;
		$result['user_id'] = isset($_SESSION['user_id']) && $result['login'] ? $_SESSION['user_id'] : NULL;
		$result['user_group'] = isset($_SESSION['user_group']) && $result['login'] ? $_SESSION['user_group'] : $this->stack['static_var']['group']['visitor'];
		$result['auth_data'] = isset($_SESSION['auth_data']) && $result['login'] ? $_SESSION['auth_data'] : NULL;
		if($result['user_group'] > $this->stack['action']['group'] && '/exception' != $this->stack['action']['path'])
		{
			$this->throwException(E_ACCESSDENIED,$this->stack['action']['path']);
		}
		return $result;
	}
}
?>
