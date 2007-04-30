<?php
/**********************************
 * Created on: 2006-12-16
 * File Name : module.post_input.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
class PostInput extends MagikeModule
{
	private $result;
	
	function __construct()
	{
		$this->result = array();
		parent::__construct(array('public' => array('database')));
	}
	
	private function getTags($tags)
	{
		str_replace("，",",",$tags);
		str_replace(" ",",",$tags);
		$tags = explode(",",$tags);

		$where = array_fill(0,count($tags),'tag_name = ?');
		if($tags)
		{
			$value = array();
			$result = 
			$this->database->fectch(array('table' => 'table.tags',
										  'where' => array('template' => implode(" OR ",$where),
														   'value'	  => $tags
									)
									));
			foreach($result as $val)
			{
				$value[$val['id']] = $val['tag_name'];
			}
			foreach($tags as $val)
			{
				if(!in_array($val,$value))
				{
					$insertId = 
					$this->database->insert(array('table' => 'table.tags',
												  'value' => array('tag_name' => $val)
											));
					$value[$insertId] = $val;
				}
			}
		}
		return $value;
	}
	
	private function deleteTags($id)
	{
		$this->database->delete(array('table' => 'table.post_tag_mapping',
									  'where' => array('template' => 'post_id = ?',
													   'value'	  => array($id)
								)
								));
	}
	
	private function insertTags($id,$tags)
	{
		foreach($tags as $key => $val)
		{
			$this->database->insert(array('table' => 'table.post_tag_mapping',
										  'value' => array('post_id' => $id,'tag_id' => $key)
									));
		}
	}
	
	public function updatePost()
	{
		$input = $_POST;
		unset($input["post_trackback"]);
		$input['post_allow_ping'] = isset($input['post_allow_ping']) ? $input['post_allow_ping'] : 0;
		$input['post_allow_comment'] = isset($input['post_allow_comment']) ? $input['post_allow_comment'] : 0;
		$input['post_allow_feed'] = isset($input['post_allow_feed']) ? $input['post_allow_feed'] : 0;
		$input['post_is_draft'] = isset($input['post_is_draft']) ? $input['post_is_draft'] : 0;
		$input['post_is_hidden'] = isset($input['post_is_hidden']) ? $input['post_is_hidden'] : 0;
		$input['post_is_page'] = isset($input['post_is_page']) ? $input['post_is_page'] : 0;
		$input['post_edit_time'] = time();
		
		$post = $this->database->fectchOne(array('table' => 'table.posts',
										 		 'where' => array('template' => 'id = ?',
														  		  'value'	 => array($_GET['post_id']))
											));
		if($post['category_id'] != $input['category_id'])
		{
			$this->database->increaseField(array('table' => 'table.categories',
												 'where' => array('template' => 'id = ?',
																  'value'	 => array($input['category_id']))),
										  'category_count'
										  );
			$this->database->decreaseField(array('table' => 'table.categories',
												 'where' => array('template' => 'id = ?',
													  			  'value'	 => array($post['category_id']))),
										  'category_count'
										  );
		}
		$this->database->update(array('table' => 'table.posts',
									  'value' => $input,
									  'where' => array('template' => 'id = ?',
													   'value'	  => array($_GET['post_id'])
								)));
		if($input['post_tags'])
		{
			$tags = $this->getTags($input['post_tags']);
			$this->deleteTags($_GET['post_id']);
			$this->insertTags($_GET['post_id'],$tags);
		}
	}
	
	public function runModule()
	{
		$this->onGet("do","updatePost","update");
		return $this->result;
	}
}
?>
