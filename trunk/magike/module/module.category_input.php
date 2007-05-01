<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.category_input.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/

class CategoryInput extends MagikeModule
{
	private $result;

	function __construct()
	{
		parent::__construct(array('public' => array('database')));
		$this->result = array();
		$this->result['open'] = false;
	}

	public function moveUpCategory()
	{
		$item = $this->database->fectch(array('table' => 'table.categories',
											  'where'	=> array('template' => 'id = ?',
																 'value'	=> array($_GET['c'])
										)));
		$item = $this->database->fectch(array('table' 	=> 'table.categories',
											  'where'	=> array('template' => 'category_sort < ?',
																 'value'	=> array($item[0]['category_sort'])
																),
											  'orderby'	=> 'category_sort',
											  'sort'	=> 'DESC',
											  'limit'	=> 1
											  )
										);
		
		if($item)
		{
			$this->database->update(array('table' => 'table.categories',
										  'where' => array('template' => 'id = ?',
															'value'	  => array($item[0]['id'])),
										  'value' => array('category_sort' => $item[0]['category_sort']+1)
										  ));
			$this->database->update(array('table' => 'table.categories',
										  'where' => array('template' => 'id = ?',
															'value'	  => array($_GET['c'])),
										  'value' => array('category_sort' => $item[0]['category_sort'])
										  ));
		}
		
		$this->result['open'] = true;
		$this->result['word'] = '您的分类已经移动成功';
	}
	
	public function moveDownCategory()
	{
		$item = $this->database->fectch(array('table' => 'table.categories',
											  'where'	=> array('template' => 'id = ?',
																 'value'	=> array($_GET['c'])
										)));
		$item = $this->database->fectch(array('table' 	=> 'table.categories',
											  'where'	=> array('template' => 'category_sort > ?',
																 'value'	=> array($item[0]['category_sort'])
																),
											  'orderby'	=> 'category_sort',
											  'sort'	=> 'ASC',
											  'limit'	=> 1
											  )
										);
		if($item)
		{
			$this->database->update(array('table' => 'table.categories',
										  'where' => array('template' => 'id = ?',
															'value'	  => array($item[0]['id'])),
										  'value' => array('category_sort' => $item[0]['category_sort']-1)
										  ));
			$this->database->update(array('table' => 'table.categories',
										  'where' => array('template' => 'id = ?',
															'value'	  => array($_GET['c'])),
										  'value' => array('category_sort' => $item[0]['category_sort'])
										  ));
		}
		
		$this->result['open'] = true;
		$this->result['word'] = '您的分类已经移动成功';
	}
	
	public function updateCategory()
	{
		$this->database->update(array('table' => 'table.categories',
									  'where' => array('template' => 'id = ?',
														'value'	  => array($_GET['c'])),
									  'value' => array('category_name' 		=> $_POST['category_name'],
													   'category_postname'	=> urldecode($_POST['category_postname']),
													   'category_describe'	=> $_POST['category_describe'])
									  ));
		
		$this->result['open'] = true;
		$this->result['word'] = '您的分类已经更新成功';
	}
	
	public function insertCategory()
	{
		$item = $this->database->fectch(array('fields'=> 'MAX(category_sort) AS max_sort',
											  'table' => 'table.categories'));
		$this->database->insert(array('table' => 'table.categories',
									  'value' => array('category_name' 		=> $_POST['category_name'],
													   'category_postname'	=> urldecode($_POST['category_postname']),
													   'category_describe'	=> $_POST['category_describe'],
													   'category_count'		=> 0,
													   'category_sort'		=> $item[0]['max_sort']+1)
									  ));
		
		$this->result['open'] = true;
		$this->result['word'] = '您插入的分类已经提交成功';
	}
	
	public function deleteCategory()
	{
		$select = is_array($_GET['c']) ? $_GET['c'] : array($_GET['c']);
		foreach($select as $id)
		{
			$categoryPosts = $this->database->fectch(array('table' => 'table.posts',
														   'where' => array('template' => 'category_id = ?',
																			'value' => array($id))));
			
			$posts = array();
			foreach($categoryPosts as $val)
			{
				$posts[] = $val['id'];
			}
			
			$this->database->delete(array('table' => 'table.categories',
												  'where' => array('template' => 'id = ?',
																	'value'	  => array($id))
												  ));
			$this->database->delete(array('table' => 'table.posts',
												  'where' => array('template' => 'category_id = ?',
																	'value'	  => array($id))
												  ));
			
			
			foreach($posts as $id)
			{
				$post = $this->database->fectchOne(array('table' => 'table.posts',
												 		 'where' => array('template' => 'id = ?',
																  		  'value'	 => array($id))
													));
				$this->database->delete(array('table' => 'table.posts',
											  'where' => array('template' => 'id = ?',
															   'value'	  => array($id)
										)));
				$this->database->delete(array('table' => 'table.comments',
											  'where' => array('template' => 'post_id = ?',
															   'value'	  => array($id)
										)));
				$this->database->decreaseField(array('table' => 'table.categories',
													 'where' => array('template' => 'id = ?',
														  			  'value'	 => array($post['category_id']))),
											  'category_count'
											  );
				if($post['post_tags'])
				{
					$this->deleteTags($id);
				}
			}
		}
		
		$this->result['open'] = true;
		$this->result['word'] = '您删除的分类已经生效';
	}
	
	public function runModule()
	{
		$this->onGet("act","moveUpCategory","up");
		$this->onGet("act","moveDownCategory","down");
		$this->onGet("act","updateCategory","update");
		$this->onGet("act","insertCategory","insert");
		$this->onGet("act","deleteCategory","del");
		return $this->result;
	}
}
?>
