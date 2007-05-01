<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.posts_list_page_nav.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class PostsListPageNav extends MagikeModule
{
	private $getArgs;
	
	function __construct()
	{
		parent::__construct(array('public' => array('database')));
	}

	public function runModule($args)
	{
		$require = array('limit'  			=> $this->stack['static_var']['post_page_num'],	//每页篇数
						 'type'				=> 0
						);
		$this->getArgs = $this->initArgs($args,$require);
		$args = array('table'	=> 'table.posts');

		switch ($this->getArgs['type'])
		{
			case 0:
				$args['where'] = array('where'	=> array(
										'template'	=> 'post_is_draft = 0 AND post_is_hidden = 0 AND post_is_page = 0'
								 ));
				break;
			case 1:
				$args['where'] = array('where'	=> array(
										'template'	=> 'post_is_draft = 0 AND post_is_page = 0'
								 ));
				break;
			case 2:
				break;
			default:
				break;
		}
		
		$total = $this->database->count($args);
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$result['next'] = $total > $page*$this->getArgs['limit'] ? $page + 1 : 0;
		$result['prev'] = $page > 1 ? $page - 1 : 0;
		$result['total'] = $total%$this->getArgs['limit'] > 0 ? intval($total/$this->getArgs['limit']) + 1
		: intval($total/$this->getArgs['limit']);
		
		return $result;
	}
}
?>
