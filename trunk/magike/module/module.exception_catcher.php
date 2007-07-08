<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.exception_catcher.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class ExceptionCatcher extends MagikeModule
{
	public function runModule()
	{
		switch ($this->stack['action']['message'])
		{
			case E_MODELFILENOTEXISTS:
			case E_PATH_PATHNOTEXISTS:
			case E_ACTION_ACTIONNOTEXISTS:
			case E_ACTION_KERNELOBJECTSNOTEXISTS:
			case E_ACTION_BUILD_MODULECLASSNOTEXISTS:
			case E_ACTION_BUILD_MODULEFILENOTEXISTS:
			case E_ACTION_JSONOUTPUT_FILENOTEXISTS:
			case E_ACTION_MODULEOUTPUT_FILENOTEXISTS:
			case E_ACTION_TEMPLATE_FILENOTEXISTS:
			case E_ACTION_TEMPLATEBUILD_CANTFINDTAG:
			case E_ACTION_TEMPLATEBUILD_INCLUDEFILENOTEXISTS:
			case E_ACTION_TEMPLATEBUILD_ASSIGNSYNTAXERROR:
			case E_ACTION_TEMPLATEBUILD_LOOPSYNTAXERROR:
			case E_ACTION_TEMPLATEBUILD_MODULEFILENOTEXISTS:
			{
				header('HTTP/1.1 404 Not Found');
				header('Status: 404  Not  Found');
				break;
			}
			case E_ACCESSDENIED:
			{
				header('HTTP/1.1 403 Forbidden');
			}
			case E_FORMISOUTOFDATE:
			{
				header('HTTP/1.1 405 Method Not Allowed');
			}
			default:
			{
				$this->stack['static_var']['blog_title'] = '错误 &raquo; '.$this->stack['static_var']['blog_name'];
				return array('data' => $this->stack['action']['data'],'message' => $this->stack['action']['message']);
				break;
			}
		}
	}
}
?>
