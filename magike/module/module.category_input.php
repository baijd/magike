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
	private $category;

	function __construct()
	{
		parent::__construct();
		$this->result = array();
		$this->result['open'] = false;
		$this->category = $this->loadModel('categories');
	}

	public function moveUpCategory()
	{
		$this->requireGet('c');
		$title = $this->category->moveUpCategory($_GET['c']);
		
		$this->result['open'] = true;
		$this->result['word'] = '您的分类 "'.$title.'" 已经移动成功';
	}
	
	public function moveDownCategory()
	{
		$this->requireGet('c');
		$title = $this->category->moveDownCategory($_GET['c']);
		
		$this->result['open'] = true;
		$this->result['word'] = '您的分类 "'.$title.'"已经移动成功';
	}
	
	public function updateCategory()
	{
		$this->requirePost();
		$this->requireGet('c');
		$this->category->updateByKey($_GET['c'], array('category_name' 		=> $_POST['category_name'],
													   'category_postname'	=> urlencode($_POST['category_postname']),
													   'category_describe'	=> $_POST['category_describe'])
									  );
		
		$this->result['open'] = true;
		$this->result['word'] = '您的分类 "'.$_POST['category_name'].'" 已经更新成功';
	}
	
	public function insertCategory()
	{
		$this->requirePost();
		$item = $this->category->fectchOne(array('fields'=> 'MAX(category_sort) AS max_sort',
											  'table' => 'table.categories'));
		$this->category->insertTable(array('category_name' 		=> $_POST['category_name'],
										   'category_postname'	=> urlencode($_POST['category_postname']),
										   'category_describe'	=> $_POST['category_describe'],
										   'category_count'		=> 0,
										   'category_sort'		=> $item['max_sort']+1));
		
		$this->result['open'] = true;
		$this->result['word'] = '您的分类 "'.$_POST['category_name'].'" 已经提交成功';
	}
	
	public function deleteCategory()
	{
		$this->requireGet('c');
		$select = is_array($_GET['c']) ? $_GET['c'] : array($_GET['c']);
		$postModel = $this->loadModel('posts');
		$tagsModel = $this->loadModel('tags');
		$commentsModel = $this->loadModel('comments');
		
		foreach($select as $id)
		{
			$categoryPosts = $postModel->fectchByFieldEqual('category_id',$id);
			
			$posts = array();
			foreach($categoryPosts as $val)
			{
				$posts[] = $val['id'];
			}
			
			$this->category->deleteByKeys($id);
			
			foreach($posts as $id)
			{
				$post = $postModel->fectchByKey($id);
				$postModel->deleteByKeys($id);
				$commentsModel->deleteByFieldEqual('post_id',$id);
				if($post['post_tags'])
				{
					$tagsModel->deleteTagsByPostId($id);
				}
			}
		}
		
		$this->result['open'] = true;
		$this->result['word'] = '您删除的分类已经生效';
	}
	
	public function runModule()
	{
		$this->onGet("do","moveUpCategory","up");
		$this->onGet("do","moveDownCategory","down");
		$this->onGet("do","updateCategory","update");
		$this->onGet("do","insertCategory","insert");
		$this->onGet("do","deleteCategory","del");
		return $this->result;
	}
}
?>
