<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.files_list_page_nav.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class FilesListPageNav extends MagikeModule
{
	public function runModule()
	{
		$fileModel = $this->loadModel('files');
		
		$total = $fileModel->countTable();
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$result['next'] = $total > $page*5 ? $page + 1 : 0;
		$result['prev'] = $page > 1 ? $page - 1 : 0;
		$result['total'] = $total%5 > 0 ? intval($total/5) + 1 : intval($total/5);
		
		return $result;
	}
}
?>
